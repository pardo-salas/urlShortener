<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="shortener">
                    <div class="section">
                        <div class="section-heading text-center">
                            <h1>Url Shortener</h1>
                            <br>
                            <div class="py-2">
                                <div class="pageIntroDescription border border-info p-3 mb-0">
                                    Professional Url Shortener, Free and fast
                                </div>
                            </div>
                            <br>
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
                                <div class="copylink mb-5"> 
                                    <span id="output_url"></span>
                                    <span id="clipboard" @click.prevent="CopyUrl">
                                        {{ copyText }}
                                    </span>
                                </div>
                            </div>
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
    authorized:Boolean
})
//Variables
let url = ref("")
let urlNotFound = ref(true)
let copyText = ref('Copy text to clipboard')
let result = ref("")
//Funciones
function shortenUrl(){

    let newUrl = url.value
    let newArray = newUrl.split('//')
    let counter = 0
    let resultNewUrl = Math.round((Math.pow(36,8) - Math.random() * Math.pow(36,8))).toString(36).slice(1)

    for (let i = 0; i < newArray.length; i++) {
        //Valida si el url comienza con http o https
        if (newArray[i] == 'http:' || newArray[i] == 'https:') {
            counter++
        }
        //Valida si el url empieza con www
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
        //Si ninguna de las validaciones es correcta, se marca como no falso 
        if(counter==0){
            urlNotFound.value = false;
        }else{
            urlNotFound.value = true;
            //Caso contrario se llama la api para la creacion del link
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