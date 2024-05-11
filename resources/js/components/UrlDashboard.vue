<template>
    <div class="container pt-2">
        <div class="row">
            <div class="col">
                <div class="stats">
                    <div class="section">
                        <div class="section-heading text-center">
                            <!-- Shortener -->            
                            <form action="" class="form">
                                <div class="d-flex flex-row align-items-center justify-content-between ">
                                    <div class="col-md-4">
                                        <input type="text" id="p1" placeholder="Put your URL " v-model="url" class="form-control addUrlInput ">
                                    </div>
                                    <div class="mx-2 d-flex align-items-center justify-content-center">
                                        <p class="border m-2 p-2 bg-secondary rounded text-light">
                                            / 20
                                        </p>
                                        <button @click.prevent="shortenUrl" class="btn btn-dark">
                                            Short
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center my-2">
                <div class="col-md-6 col-12 row border rounded p-2 " v-for="link in links" :key="link.id">
                    <div class="mt-2 d-flex item-align-cener justify-content-between">
                        <a :href="link.new_url" target="_blank" class="text-decoration-none fs-3">
                            <span class="opacity-40">/</span>
                            <span>
                                {{ link.shorturl }}
                            </span>
                        </a>
                        <div class="d-flex justify-content-end">
                            <button @click="urlSelected=link" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#confirmModal"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M9 3v1H4v2h1v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6h1V4h-5V3zM7 6h10v13H7zm2 2v9h2V8zm4 0v9h2V8z"/></svg></button>
    
                            <button class="btn btn-light"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="currentColor" d="M216 28H88a12 12 0 0 0-12 12v36H40a12 12 0 0 0-12 12v128a12 12 0 0 0 12 12h128a12 12 0 0 0 12-12v-36h36a12 12 0 0 0 12-12V40a12 12 0 0 0-12-12m-60 176H52V100h104Zm48-48h-24V88a12 12 0 0 0-12-12h-68V52h104Z"/></svg></button>
                        </div>
                    </div>
                    <span class="mb-2 d-inline-block text-truncate block fs-5 opacity-50">
                        {{ link.old_url }}
                    </span>
                    <p class="d-flex justify-content-end">
                        {{ link.created_at }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="confirmModal">Are you sure?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column">
                    <span class="text-danger mb-3">Access to the link will be permanently removed. This action cannot be undone.</span>
                    <span class="my-2 fw-bold">Type {{ urlSelected.shorturl }} to confirm</span>
                    <input class="form-control"type="text" v-model="inputConfirm">
                </div>
                <div class="modal-footer">
                    <button @click="urlSelected=''" id="button-dismiss" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button @click="handleDelete(inputConfirm)" type="button" class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M9 3v1H4v2h1v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6h1V4h-5V3zM7 6h10v13H7zm2 2v9h2V8zm4 0v9h2V8z"/></svg> Delete</button>
                </div>
            </div>
        </div>
        <div v-if="showAlert" class="d-flex justify-content-end fixed-bottom mx-2">
            <alert type="danger">
                The short url doesn't match
            </alert>
        </div>
    </div>
    <div v-if="showAlertSuccess"class="d-flex justify-content-end fixed-bottom mx-2">
        <alert type="success">
            The short url have been deleted successfuly
        </alert>
    </div>
</template>

<script setup>
import { ref,defineProps,onMounted } from 'vue';

const props =defineProps({
    authorized:String,
    links:String
})

const links = ref();

//Variables
let url = ref("")
let urlNotFound = ref(true)
let result = ref("")
let urlSelected=ref("")
let inputConfirm=ref("")
let showAlert = ref(false)
let showAlertSuccess = ref(false)
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
            let currentUrl = resultNewUrl;

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

function handleDelete(id){
    if (id != urlSelected.value.shorturl) {
        showAlert.value = true;
        setTimeout(() => {
            showAlert.value=false;
        }, 5000);
        return
    }
        axios.delete(`/url/delete/${urlSelected.value.id}`).then(function(response){
            result = response.data
            formatDates(result)
        })
        let closeModal= document.getElementById('button-dismiss');
        closeModal.click()
        showAlertSuccess.value = true;
        setTimeout(() => {
            showAlertSuccess.value=false;
        }, 5000);
}

function formatDates(array){
    array.forEach((link)=>{
        const date = new Date(link.updated_at);
        const date_created = new Date(link.created_at);
        link.updated_at = date.toDateString()
        link.created_at = date_created.toDateString()
        link.shorturl=link.new_url.substring(link.new_url.length-7)
    })
    links.value=array
}
//
onMounted(()=>{
    formatDates(JSON.parse(props.links));
})
</script>

<style scoped> 

</style>