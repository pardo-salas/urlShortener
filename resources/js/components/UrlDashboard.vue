<template>
    <div class="container pt-2">
        <div class="row justify-content-center">
            <div class="col">
                <div class="stats">
                    <div class="section">
                        <div class="section-heading text-center">
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
                                <!-- <div class="copylink mb-5"> 
                                    <span id="output_url"></span>
                                    <span id="clipboard" @click.prevent="CopyUrl">
                                        {{ copyText }}
                                    </span>
                                </div> -->
                                
                            </div>
                            <!-- Link register -->
                            <div v-else>
                                <h5>You are required to register </h5>
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
        <div class="row">
            <div class="col d-flex flex-wrap justify-content-center">
                <div class="col-5 row p-2 border m-1 flex-grow-1" v-for="link in links" :key="link.id">
                    <div class="d-flex justify-content-between align-items-center">
                        <p>
                            {{ link.new_url }}
                        </p>
                        <div>
                            <button class="btn btn-light"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="currentColor" d="M216 28H88a12 12 0 0 0-12 12v36H40a12 12 0 0 0-12 12v128a12 12 0 0 0 12 12h128a12 12 0 0 0 12-12v-36h36a12 12 0 0 0 12-12V40a12 12 0 0 0-12-12m-60 176H52V100h104Zm48-48h-24V88a12 12 0 0 0-12-12h-68V52h104Z"/></svg></button>
                            {{ link.updated_at }}
                        </div>
                    </div>
                    <p class="texto-acortado">
                        {{ link.old_url }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref,defineProps,onMounted } from 'vue';

const props =defineProps({
    authorized:Number,
    links:Object
})

const links = ref();

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

onMounted(()=>{
    links.value=JSON.parse(props.links);
    links.value.forEach((link)=>{
        const date = new Date(link.updated_at);
        link.updated_at = date.toDateString()
    })
})
</script>

<style scoped> 
.texto-acortado{
  width: 200px; 
  white-space: nowrap; 
  overflow: hidden; 
  text-overflow: ellipsis;
}
</style>