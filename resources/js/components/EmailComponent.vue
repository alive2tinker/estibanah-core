<template>
<div>
    <label for="form-answer-link">{{ $t("Simply copy the link")}}</label>
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="form_link" id="form-answer-link" :value="link">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="button-addon2" @click="copyText"><i class="fa fa-clone"></i></button>
        </div>
    </div>
    <label for="email-list">{{ $t("Or add list of emails separated by a comma")}}</label><span v-if="emailList != ''" :class="{'badge': true, 'badge-success': validEmailList, 'badge-danger': !validEmailList}">{{ validEmailList ? $t("Good Format") : $t("Bad format")}}</span>
    <textarea v-model="emailList" id="email-list"
              cols="30" rows="10"
              class="form-control"></textarea>
    <button class="btn btn-primary btn-block my-2" @click="submitForm" type="button" :disabled="!validEmailList || isLoading">
        <object v-if="isLoading">
            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            Loading...
        </object>
        <object v-else>
            Send Emails
        </object>
    </button>
</div>
</template>

<script>
export default {
    name: "EmailComponent.vue",
    props: ["link","title"],
    data(){
        return {
            isLoading: false,
            emailList: '',
            reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
        }
    },
    computed: {
        validEmailList: function(){
            var res = true;
            if(this.emailList === '')
                return false;
            var emails = this.emailList.split(',');
            emails.forEach(email => {
                email = email.replace(/\s/g, '');
                if(!this.reg.test(email))
                    res = res && false;
            });

            return res;
        }
    },
    methods: {
        copyText: function(){
            this.link.select();
            document.execCommand("copy");
        },
        submitForm: function(e){
            e.preventDefault();
            this.isLoading = true;
            var fd = new FormData();
            fd.append('emails', this.emailList);
            fd.append('link', this.link);
            fd.append('title', this.title);

            axios.post('/send_invitations', fd).then((response) => {
                this.$swal.fire(this.$t('Great!'), this.$t('emails have been sent successfully'), 'success');
                this.isLoading = false;
            }).catch((error) => {
                this.$swal.fire('Oops!', error.response.data.message, 'error');
                this.isLoading = false;
            });
        }
    }
}
</script>

<style scoped>
.loader-background{
    position:absolute;
    background: #fff;
    width: 100%;
    top: 0;
    bottom: 0;
    left:0;
    right:0;
    z-index: 2147483647 !important;
}
.loader-container {
    width: 144px;
    height: 100px;
    position: absolute;
    margin: 0 auto;
    z-index: 99999999999 !important;
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
</style>
