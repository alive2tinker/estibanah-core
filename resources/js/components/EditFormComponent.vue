<template>
    <div class="container">
        <div :class="{'my-4 alert': true, 'alert-success': success}" v-if="status">{{ status }}</div>
        <div class="border-bottom">
            <h1>Editing {{ form.title }}</h1>
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
        <h1 class="my-4">Questions</h1>
        <div class="row">
            <div class="col-md-11">
                <div class="card" v-for="(question, index) in questions">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Question {{ index + 1}}</h3>
                            <i class="fa fa-trash" @click="deleteQuestion(question, index)"></i>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10">
                                <label :for="`question-${index}-text`">Text</label>
                                <div class="form-group"><input v-model="question.text" class="form-control" :id="`question-${index}-text`" type="text"></div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea v-model="question.description" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" :checked="question.required" v-model="question.required" :id="`question-${index}-requirement`">
                                        <label class="form-check-label" :for="`question-${index}-requirement`">
                                            required?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2"><label :for="`question-${index}-type`">Type</label>
                                <select @change="toggleAnswers(question)" v-model="question.type" class="form-control" :id="`question-${index}-type`">
                                    <option value="">choose</option>
                                    <option value="short_answer">Short Answer</option>
                                    <option value="long_answer">Long Answer</option>
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="file">File</option>
                                    <option value="date">Date</option>
                                </select>
                            </div>
                        </div>
                        <div v-if="question.type === 'checkbox' || question.type === 'multiple_choice'" class="my-2">
                            <div class="form-check" v-for="(answer, index) in question.answers">
                                <input class="form-check-input" type="checkbox" disabled>
                                <label class="form-check-label">
                                    <input type="text" class="form-control borderless" placeholder="write answer" v-model="answer.text">
                                    <i class="fa fa-trash" @click="deleteAnswer(question, index)"></i>
                                </label>
                            </div>
                            <button class="btn btn-link" @click="addAnswer(question)">Add Answer</button>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center my-3">
                    <div class="col-md-5">
                        <button class="btn btn-primary btn-lg btn-block" @click="submitForm">Update Form</button>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <ul class="actions">
                    <li>
                        <button class="btn btn-link newQueBtn" @click="addQuestion"><i class="fa fa-plus"></i></button></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "EditFormComponent",
    props: ['form'],
    data(){
        return {
            title: '',
            description: '',
            questions: [{
                text: '',
                type: '',
                answers:[],
                description: '',
                required: 0
            }],
            loading: false,
            errors: [],
            success: false,
            status: ''
        }
    },
    created() {
        this.getData();
    },
    methods: {
        getData: function(){
            axios.get(`/forms/resource/${this.form}`).then((response) => {
                this.title = response.data.data.title;
                this.description = response.data.data.description;
                this.questions = response.data.data.questions;
                this.loading = false;
            })
        },
        toggleAnswers: function(question){
            if(question.type === 'multiple_choice' || question.type === 'checkbox'){
                if(question.answers.length === 0){
                    question.answers.push({text: ''});
                }
            }else{
                if(question.answers.length > 0) {
                    this.$swal.fire({
                        title: 'Are you sure?',
                        text: "All answers will be deleted, and you will not be able to recover them",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change question type'
                    }).then((result) => {
                        if (result.value) {
                            for(var i = 0; i < question.answers.length; i++){
                                this.deleteAnswer(question, i)
                            }
                            this.$swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        }
                    });
                }
            }
        },
        addAnswer: function(question){
            question.answers.push({text: ''});
        },
        deleteAnswer: function(question, index){
            if(question.answers.length > 1){
                //delete from server
                if(question.answers[index].id !== null)
                    axios.delete(`/answers/${question.answers[index].id}`).then((response) => {
                        question.answers.splice(index, 1);
                    }).catch((error) => {
                        question.answers.splice(index, 1);
                    });
                else
                    question.answers.splice(index, 1);
            }
        },
        deleteQuestion: function(question, index){
            if(this.questions.length > 1){
                //delete from server
                if(question.id)
                    axios.delete(`/questions/${question.id}`).then((response) => {
                        this.questions.splice(index, 1);
                    });
                else
                    this.questions.splice(index, 1);
            }

        },
        addQuestion: function(){
            this.questions.push({
                text: '',
                type: '',
                description: '',
                required: 0,
                answers:[]
            });
        },
        submitForm: function(){
            var formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('questions', JSON.stringify(this.questions));
            formData.append('_method','PATCH');

            axios.post(`/forms/${this.form}`, formData).then((repsonse) => {
                this.status = "form updated successfully. redirecting shortly";
                this.success = true;
                setInterval(() => {
                    window.location = '/forms/'+this.form;
                }, 5000)
            }).catch((error) => {
                console.log(error);
            })
        }
    }
}
</script>

<style scoped>
.actions{
    list-style-type: none;
    padding: 0;
    margin: 0;
    float: left;
}
.actions li{
    width: 100%;
    background: #ffffff;
    border: 0.5px solid #444444;
}
.actions li a{
    color: black;
}
.borderless{
    border: 0;
    outline: none!important;
}
.newQueBtn{
    position: fixed;
}
</style>
