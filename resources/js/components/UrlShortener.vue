<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="shortener">
                    <div class="section">
                        <div class="section-heading text-center">
                            <h1 class="fs-1 fw-bold">Surl URL shortener</h1>
                            <br>
                            <!-- Title -->
                            <div class="py-2">
                                <div class="pageIntroDescription border border-info p-3 mb-0">
                                    Simple and fast URL shortener! Url Short allows to shorten long links from Instagram, Facebook, YouTube, Twitter, Linked In, WhatsApp, TikTok, blogs and sites.
                                </div>
                            </div>
                            <br>
                            <!-- Shortener -->
                            <div v-if="props.authorized">
                                <form action="" class="form">
                                    <div class="input-group">
                                        <input type="text" id="p1" placeholder="Put your URL " v-model="url" class="form-control addUrlInput">
                                    </div>
                                    <div>
                                        <button @click.prevent="shortenUrl" class="btn btn-dark my-3">
                                            Short
                                        </button>
                                    </div>
                                </form>
                                <br>
                                <p v-if="!urlNotFound" class="alert alert-danger">
                                    Url is not valid
                                </p>
                                <!-- Section copy button -->
                                <div class="copylink mb-5"> 
                                    <span id="output_url"></span>
                                    <span id="clipboard" @click.prevent="CopyUrl">
                                        {{ copyText }}
                                    </span>
                                </div>
                            </div>
                            <!-- Link register -->
                            <div v-else>
                                <h5>You are required to register </h5>
                                <hr>
                                To shorten your urls 
                                <hr>
                                <a href="/register">
                                    <small>Register Here</small>
                                </a>
                                or
                                <a href="/login">
                                    <small>Login Here</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref,defineProps } from 'vue';

const props =defineProps({
    authorized:Number
})

//Variables
let url = ref("")
let urlNotFound = ref(true)
let copyText = ref('Copy text to clipboard')
let result = ref("")
//Functions
function shortenUrl(){
    let newUrl = url.value
    let newArray = newUrl.split('//')
    let counter = 0
    let resultNewUrl = Math.round((Math.pow(36,8) - Math.random() * Math.pow(36,8))).toString(36).slice(1)

    for (let i = 0; i < newArray.length; i++) {
        //Validate if the urls is rigth
        if (newArray[i] == 'http:' || newArray[i] == 'https:') {
            counter++
        }
        //Validate if the url starts with wwww
        if (counter==0) {
            let newArrayOne = newUrl.split('.')
            if (newArrayOne[i]=='www') {
                counter++
            }
            let newArrayTwo = newUrl.indexOf('.com')
            if (newArrayTwo>=0) {
                counter++
            }
        }
        //If validations is 0 then value is false
        if(counter==0){
            urlNotFound.value = false;
        }else{
            urlNotFound.value = true;
            let currentUrl = window.location.href+'u/'+resultNewUrl;

            axios.post('/url/shorten',{
                url:newUrl,
                shortlink:currentUrl
            }).then(function(response){
                result = response.data
                $('.copylink').fadeIn(500);
                $('.copylink').siblings('.form').find("#p1").val(result)
            });
        }
    }
}

function CopyUrl(){
    $("#p1").select();
    copyText = "Url coppied successfully"
    document.execCommand("copy");
    url.value = result
}
</script>

<style scoped>
    .copylink{
        display: none;
    }
    #clipboard{
        display: block;
        margin-top: 28px;
        background-color: #03cbf8;
        color: #fff;
        font-weight: 900;
        font-size: 17px;
    }
    #clipboard:hover{
        background-color: #333;
    }
    #clipboard:visited, #clipboard:active, #clipboard:focus{
        background-color: green;
        color: #333;
    }

</style>