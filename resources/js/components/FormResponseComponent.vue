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
            <h1>{{ title }}</h1>
            <p class="text-muted">{{ description !== "null" ? description : 'no description' }}</p>
            <div :class="{'card': true, 'hasError': hasError(index)}" v-for="(response, index) in responses" :key="response.question.id" v-show="parse(response.question.conditions)">
                <div class="card-body">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="hasError(index)">
                        <strong>Oops!</strong> {{  getErrorMessage(index)  }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <h3 class="card-title">{{ response.question.text }}</h3>
                    <input type="text" class="form-control" v-show="response.question.type === 'short_answer'" v-model="response.value">
                    <input type="date" class="form-control" v-show="response.question.type === 'date'" v-model="response.value">
                    <input type="file" class="form-control" v-show="response.question.type === 'file'" ref="file" multiple @change="pushFiles($event, response)">
                    <textarea v-model="response.value" cols="30" rows="10" class="form-control" v-show="response.question.type === 'long_answer'"></textarea>
                    <div class="form-check"
                         v-show="response.question.type === 'multiple_choice'"
                         v-for="answer in response.question.answers" :key="answer.id">
                        <input type="radio" :name="`response-${response.id}-answers`" :id="`answer-${answer.id}`" :value="answer.text" v-model="response.value">
                        <label :for="`one-${answer.id}`">{{  answer.text  }}</label>
                    </div>
                    <div class="form-check"
                         v-show="response.question.type === 'checkbox'"
                         v-for="answer in response.question.answers" :key="`answer-${answer.id}`">
                        <input type="checkbox" :value="answer.text" v-model="response.value">
                        <label :for="`one-${answer.id}`">{{  answer.text  }}</label>
                    </div>
                </div>
            </div>
            <div class="row py-3 justify-content-center">
                <div class="col-md-5">
                    <button type="button" class="btn btn-primary btn-block btn-lg" @click="submitResponse($event)">Submit</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "FormResponseComponent",
    props:['form'],
    data(){
        return {
            title: '',
            description: '',
            isLoading: true,
            responses: [],
            errors: [],
            files: []
        }
    },
    mounted(){
        this.syncData();
    },
    methods: {
        syncData: function(){
            this.isLoading = true;
            axios.get(`/forms/resource/${this.form}`).then((response) => {
                this.title = response.data.data.title;
                this.description = response.data.data.description;
                response.data.data.questions.forEach(q => {
                    if(q.type === 'checkbox' || q.type === 'file')
                        this.responses.push({question: q, value: []})
                    else
                        this.responses.push({question: q, value: ''})
                })
                this.isLoading = false;
            });
        },
        parse: function(conditions){
            if(conditions !== null)
                return eval(conditions);
            else
                return true;
        },
        isNotEmpty: function(text){
            var response = this.responses.find((response) => response.question.text === text);
            return response.question.type === 'checkbox' ? response.value.length > 0
                : response.value !== '';
        },
        answerEquals: function(text, value){
            var response = this.responses.find((response) => response.question.text === text);
            return resoonse.value === value;
        },
        doesNotAnswerEquals: function(text, value){
            var response = this.responses.find((response) => response.question.text === text);
            return resoonse.value === value;
        },
        pushFiles: function(event, response)
        {
            Array.from(event.target.files).forEach(file => {
                this.files.push(file);
                response.value.push(file.name);
            });
        },
        isAValidResponse: function(){
            var res = true;

            this.responses.forEach((response, index) => {
                if(response.question.required && (response.value === '' || response.value === [])){
                    window.scrollTo(0,0);
                    this.errors.push({key:index, message: `${response.question.text} is required`});
                    res = res && false;
                }
            });

            return res;
        },
        submitResponse: function(event){
            event.preventDefault();
            if(this.isAValidResponse()){
                let formData = new FormData();
                this.files.forEach((file, index) => {
                    formData.append('files['+index+']', file);
                });
                formData.append('responses', JSON.stringify(this.responses));
                axios.post(`/forms/${this.form}`, formData).then((response) => {
                    this.$swal.fire({
                        title: "Great!",
                        text: "Form created successfully! redirecting you shortly",
                        icon: "success"
                    }).then((result) => {
                        if(result.isConfirmed)
                            window.location = '/forms/thanks';
                    });
                })
            }
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
    }
}
</script>
<style scoped>
    .card{
        animation: fadeIn 0.5s ease-in;
    }
    @keyframes fadeIn {
        from{opacity: 0;}
        to{opacity: 1}
    }
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
