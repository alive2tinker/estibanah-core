<template>
    <div class="container">
        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-for="error in errors">
            <strong>Oops!</strong> {{  error  }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="border-bottom">
            <h1>New Form</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="form-group"><label for="form-title">Title</label><input v-model="title" class="form-control" id="form-title"
                                                                                    type="text"></div>
                <div class="form-group"><label for="form-description">Description</label><textarea class="form-control"
                                                                                                   v-model="description"
                                                                                                   cols="30"
                                                                                                   id="form-description" name=""
                                                                                                   rows="4"></textarea>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-10" @drop="addQuestion" @dragover="allowDrop">
                <div class="card" v-for="(question, index) in questions">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Question {{ index + 1}}
                                <small class="badge badge-secondary">{{ question.type }}</small>
                            </h4>
                            <i class="fa fa-trash" @click="deleteQuestion(index)"></i>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label :for="`question-${index}-text`">Text</label>
                                <div class="form-group"><input v-model="question.text" class="form-control" :id="`question-${index}-text`" type="text"></div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea v-model="question.description" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div v-if="question.type === 'checkbox' || question.type === 'multiple_choice'" class="my-2">
                            <div class="input-group mb-3" v-for="(answer, index) in question.answers">
                                <input type="text" class="form-control" v-model="answer.text" placeholder="answer" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="button-addon2"><i class="fa fa-trash" @click="deleteAnswer(question, index)"></i></button>
                                </div>
                            </div>
                            <button class="btn btn-link" @click="addAnswer(question)">Add Answer</button>
                        </div>
                    </div>
                    <div class="card-footer">
                        <ul class="list-unstyled">
                            <li class="d-inline-block">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" :checked="question.required" v-model="question.required" :id="`question-${index}-requirement`">
                                <label class="form-check-label" :for="`question-${index}-requirement`">
                                    required?
                                </label>
                            </div></li>
                            <li class="d-inline-block">
                                <b-button variant="link" v-show="index >= 1" @click="openConditionsModal(index)">conditions</b-button>

                                <b-modal size="lg" :id="`conditions-modal-q${index}`" title="Conditions Modal">
                                    <div class="input-group" v-for="condition in question.conditions">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">question</span>
                                        </div>
                                        <select v-model="condition.question" class="form-control">
                                            <option v-for="question in availableQuestions" v-if="question !== condition.question"
                                                    :value="question.text">{{ question.text }}</option>
                                        </select>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">condition</span>
                                        </div>
                                        <select v-model="condition.operation" class="form-control">
                                            <option value="notEmpty">Not Empty</option>
                                            <option value="answerEquals">Answer Equals</option>
                                            <option value="!answerEquals">Answer Does not Equal</option>
                                        </select>
                                        <div class="input-group-prepend" v-show="condition.operation === 'answerEquals' || condition.operation === '!answerEquals'">
                                            <span class="input-group-text">value</span>
                                        </div>
                                        <input type="text" v-show="condition.operation === 'answerEquals' || condition.operation === '!answerEquals'" v-model="condition.value" class="form-control">
                                    </div>
                                    <button class="btn btn-link" @click="addCondition(question, index)">add condition</button>
                                </b-modal>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="muted-background py-4"><h3 class="text-muted text-center">Add your questions</h3></div>
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <button class="btn btn-primary btn-block" @click="submitForm" type="button">Submit</button>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card position-fixed">
                    <div class="card-body">
                        <ul class="m-0 p-0 q-pane">
                            <li draggable="true" @dragstart="prepareToAddQuestion" id="short_answer" class="badge badge-pill badge-primary">Short Answer</li>
                            <li draggable="true" @dragstart="prepareToAddQuestion" id="long_answer" class="badge badge-pill badge-primary">Long Answer</li>
                            <li draggable="true" @dragstart="prepareToAddQuestion" id="multiple_choice" class="badge badge-pill badge-primary">Multiple Choice</li>
                            <li draggable="true" @dragstart="prepareToAddQuestion" id="checkbox" class="badge badge-pill badge-primary">Checkboxes</li>
                            <li draggable="true" @dragstart="prepareToAddQuestion" id="file" class="badge badge-pill badge-primary">File</li>
                            <li draggable="true" @dragstart="prepareToAddQuestion" id="date" class="badge badge-pill badge-primary">Date</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "FormComponent",
    data(){
        return {
            title: '',
            description: '',
            questions: [],
            errors: [],
        }
    },
    computed: {
        availableQuestions: function(question){
            var availableQuesitons = this.questions.slice();
            var index = this.questions.findIndex((s) => s.text === question.text);
            availableQuesitons.splice(index, 1);

            return availableQuesitons;
        }
    },
    methods: {
        validateInput: function(){
            if(this.questions.length === 0){
                this.errors.push("you must have at least one question");
                return false;
            }

            this.questions.forEach((q, index) => {
                if((q.type === 'multiple_choice' || q.type === 'checkbox') && q.answers.length === 0){
                    this.errors.push("question titled: "+q.title+" must have at least one answer");
                    return false;
                }

                if(q.title === ''){
                    this.errors.push("question "+index+" must have a title");
                    return false;
                }
            });

            return true;

        },
        allowDrop: function (e){
            e.preventDefault();
        },
        prepareToAddQuestion: function (e){
            e.dataTransfer.setData("question-type", e.target.id);
        },
        addQuestion: function(e){
            e.preventDefault();
            var questionType = e.dataTransfer.getData('question-type');
            if(questionType == "multiple_choice" || questionType == "checkbox")
            {
                this.questions.push({
                    text: "untitled question",
                    description: "question description",
                    required: false,
                    type:questionType,
                    answers: [
                        {text:"untitled answer"}
                    ],
                    conditions:[]
                })
            }else{
                this.questions.push({
                    text: "untitled question",
                    description: "question description",
                    required: false,
                    type:questionType,
                    conditions: []
                });
            }
        },
        deleteQuestion: function(index){
            if(this.questions.length > 1)
                this.questions.splice(index, 1);
        },
        addAnswer: function(question){
            question.answers.push({text: ''});
        },
        deleteAnswer: function(question, index){
            if(question.answers.length > 1)
                question.answers.splice(index, 1);
        },
        openConditionsModal: function(index){
            this.$bvModal.show(`conditions-modal-q${index}`);
        },
        addCondition: function(question, index){
            if(this.canAddCondition(question))
            {
                question.conditions.push({question: null, operation: null, value: null})
            }else{
                //this.$bvModal.hide(`conditions-modal-q${index}`);
                this.$swal.fire('Error', "there are no more questions to add conditions for", 'error');
            }
        },
        canAddCondition: function(question)
        {
            var res = true;

            if(question.conditions.length >= this.availableQuestions.length)
                res = false;

            this.questions.forEach(q => {
                if(q.title === '')
                    res = res && false;
            });

            return res;
        },
        submitForm: function(){
            var formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('questions', JSON.stringify(this.questions));

            axios.post('/forms', formData).then((repsonse) => {
                this.$swal.fire('Great!', "Form created successfully! redirecting you shortly", 'success');
                setInterval(() => {
                    window.location = '/forms/'+repsonse.data.id;
                }, 5000, repsonse);
            })
        },
    }
}
</script>
<style scoped>
.q-pane li{
    display: block;
    margin-top: 0.5em;
    margin-bottom: 0.5em;
}
</style>
