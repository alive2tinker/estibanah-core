<template>
    <div>
        <div class="loader-background" v-if="isLoading">
            <div class="loader-container">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="container">
            <div :class="{'card':true, 'hasError': hasError('title')}">
                <div class="card-body">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="hasError('title')">
                        <strong>Oops!</strong> {{  getErrorMessage('title')  }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
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
                <div class="col-md-10">
                    <div :class="{'card':true, 'hasError' : hasError(index)}" v-for="(question, index) in questions">
                        <div class="card-body">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="hasError(index)">
                                <strong>Oops!</strong> {{  getErrorMessage(index)  }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title">Question {{ index + 1}}
                                    <small class="badge badge-secondary"><i :class="getIcon(question.type)"></i></small>
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
                </div>
                <div class="col-md-2">
                    <div class="card position-fixed">
                        <div class="card-body">
                            <button
                                type="button"
                                class="btn btn-block btn-primary"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="add a short answer question"
                                @click="addQuestion('short_answer')">
                                <i class="fa fa-font"></i></button>
                            <button
                                type="button"
                                class="btn btn-block btn-primary"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="add a long answer question"
                                @click="addQuestion('long_answer')">
                                <i class="fa fa-align-justify"></i></button>
                            <button
                                type="button"
                                class="btn btn-block btn-primary"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="add a multiple choice question"
                                @click="addQuestion('multiple_choice')">
                                <i class="fa fa-list-ul"></i></button>
                            <button
                                type="button"
                                class="btn btn-block btn-primary"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="add a checkbox question"
                                @click="addQuestion('checkbox')">
                                <i class="fa fa-check-circle-o"></i></button>
                            <button
                                type="button"
                                class="btn btn-block btn-primary"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="add a question with upload"
                                @click="addQuestion('file')">
                                <i class="fa fa-file-o"></i></button>
                            <button
                                type="button"
                                class="btn btn-block btn-primary"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="add a date question"
                                @click="addQuestion('date')">
                                <i class="fa fa-calendar"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 justify-content-center">
                <div class="col-md-5">
                    <button class="btn btn-primary btn-block" type="button" @click="submitForm">Submit</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "EditFormComponent",
    props: ['form'],
    data() {
        return {
            title: '',
            description: '',
            questions: [{
                text: "",
                description: "",
                required: false,
                type:"short_answer",
                conditions: []
            }],
            errors: [],
            isLoading: true
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
    mounted(){
        this.getData();
    },
    methods: {
        getData: function(){
            axios.get(`/forms/resource/${this.form}`).then((response) => {
                this.title = response.data.data.title;
                this.description = response.data.data.description;
                this.questions = response.data.data.questions;
                this.isLoading = false;
            })
        },
        validateInput: function(){
            var res = true;
            if(this.title === ''){
                this.errors.push({key: 'title', message:"title cannot be empty"});
                res = res && false;
            }

            if(this.questions.length > 0){
                this.questions.forEach((q, index) => {
                    if(q.text === ''){
                        this.errors.push({key: index, message: "title cannot be empty for "+(index+1)});
                        res = res && false;
                    }
                })
            }

            return res;
        },
        addQuestion: function(type){
            if(type === "multiple_choice" || type === "checkbox")
            {
                this.questions.push({
                    text: "",
                    description: "",
                    required: false,
                    type:type,
                    answers: [
                        {text:""}
                    ],
                    conditions:[]
                })
            }else{
                this.questions.push({
                    text: "",
                    description: "",
                    required: false,
                    type:type,
                    conditions: []
                });
            }
        },
        deleteQuestion: function(index){
            if(this.questions.length > 1)
                this.questions.splice(index, 1);
            else
                this.errors.push({key: index, message:"cannot delete the only question"})
        },
        addAnswer: function(question){
            question.answers.push({text: ''});
        },
        deleteAnswer: function(question, index){
            if(question.answers.length > 1)
                question.answers.splice(index, 1);
            else
                this.errors.push({key: index, message:"cannot delete the only answer"})
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
        hasError: function(index){
            var res = false;
            this.errors.forEach((err) => {
                if(err.key === index)
                    res= true;
            });
            return res;
        },
        getErrorMessage: function(index){
            var res = false;
            this.errors.forEach((err) => {
                if(err.key === index)
                    res = err.message;
            });
            return res;
        },
        getIcon: function(type){
            switch (type){
                case 'short_answer':
                    return 'fa fa-font';
                    break;
                case 'long_answer':
                    return 'fa fa-align-justify';
                    break
                case 'multiple_choice':
                    return 'fa fa-list-ul';
                    break;
                case 'checkbox':
                    return 'fa fa-check-circle-o';
                    break;
                case 'file':
                    return 'fa fa-file-o';
                    break;
                case 'date':
                    return 'fa fa-calendar';
                    break;
            }
        },
        submitForm: function(e){
            e.preventDefault();
            var formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('questions', JSON.stringify(this.questions));
            formData.append('_method','PATCH');

            axios.post(`/forms/${this.form}`, formData).then((repsonse) => {
                this.isLoading = false;
                this.$swal.fire({
                    title: "Great!",
                    text: "Form updated successfully! redirecting you shortly",
                    icon: "success"
                }).then((result) => {
                    if(result.isConfirmed)
                        window.location = '/forms/'+repsonse.data.id;
                });
            }).catch((error) => {
                this.isLoading = false;
                this.$swal.fire('Oops!', error.response.data.message, 'error');
            })
        }
    }
}
</script>
<style scoped>
.loader-container {
    width: 144px;
    height: 100px;
    position: relative;
    margin: 0 auto;
}
.loader-container span {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    position: absolute;
    bottom: 0;
    left: 0;
    display: block;
    background-color: #9B5986;
    animation: Loading 3s infinite ease-in-out;
}
.loader-container span:nth-of-type(2) { left: 30px; animation-delay: .2s; }
.loader-container span:nth-of-type(3) { left: 60px; animation-delay: .4s; }
.loader-container span:nth-of-type(4) { left: 90px; animation-delay: .6s; }
.loader-container span:last-of-type { left: 120px; animation-delay: .8s; }

@keyframes Loading {
    0% { height: 20px; transform: translateY(0); background-color: #9B59B6; }
    50% { height: 20px; transform: translateY(40px); background-color: #3498D6; }
    100% { height: 20px; transform: translateY(0); background-color: #9B59B6; }
}
.loader-background{
    background: rgba(255,255,255,0.6);
    z-index: 99999999999;
    width: 100%;
    height: 100vh;
    top: 0;
    position: absolute;
    padding-top: 25%;
}
.hasError{
    border: 1px solid red;
}
.hasError > div.body > input{
    border: 1px solid red;
}
</style>
