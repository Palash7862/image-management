<template> 
    <Fragment>
        <v-card  elevation="1">
            <v-toolbar color="primary" dark flat >
                <v-toolbar-title>Images List</v-toolbar-title>
                <v-spacer></v-spacer> 
                <v-btn color="success" @click="dialog = true">Add New Image</v-btn>
            </v-toolbar>
            <v-card-text>
                <v-simple-table>
                    <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left">Title </th>
                            <th class="text-left">Image Orginal Source</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in allImages" :key="item.name" >
                            <td class="text-left">{{ item.title }}</td>
                            <td class="text-left">{{ item.image_url }}</td>
                            <td>{{ item.status }}</td>
                        </tr>
                    </tbody>
                    </template>
                </v-simple-table>
            </v-card-text>
        </v-card>
        <v-dialog v-model="dialog" width="500" > 
            <v-card :loading="isLoading">
                <v-card-title class="headline grey lighten-2">
                    Image Information
                </v-card-title>

                <v-card-text>
                    <v-form ref="form">
                        <v-text-field label="Image Title" name="title" prepend-icon="mdi-account" type="text" v-model="form.title" :error-messages="errors.title"></v-text-field> 
                        <v-text-field label="Image Url" name="image_url" prepend-icon="mdi-account" type="text" v-model="form.image_url" :error-messages="errors.image_url"></v-text-field> 
                    </v-form>
                </v-card-text>

                <v-divider></v-divider> 
                <v-card-actions>
                    <v-btn color="primary" text @click="dialog = false" >
                        Close
                    </v-btn>
                    <v-spacer></v-spacer> 
                    <v-btn color="success" text @click="storeImage"> Submit </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar" top color="success" timeout="-1">
            {{ snackbarText }} 
            <template v-slot:action="{ attrs }">
                <v-btn color="primary" text v-bind="attrs" @click="snackbar = false"> Close </v-btn>
            </template>
        </v-snackbar>
    </Fragment>
</template>

<script> 
    import { Fragment } from 'vue-fragment'
    export default {
        name: "UserImage",
        components: {Fragment}, 
        data(){
            return {
                token: localStorage.getItem('token'), 
                allImages: [],  
                dialog: false,
                form: {
                    title:'', 
                    image_url: '', 
                }, 
                errors: {}, 
                isLoading: false, 
                snackbar: false, 
                snackbarText: 'Successfully',
            }
        }, 
        methods: {
            getUserImages(){
                axios.get('/api/image').then(res => {
                    this.allImages = res.data.data;
                });
            }, 
            storeImage(){
                this.isLoading = 'red';
                axios.post('/api/image/store', this.form).then(res => { 
                    this.snackbar = true;
                    this.snackbarText = res.data.message;
                    this.$refs.form.reset();
                    this.$refs.form.resetValidation();
                    this.dialog = false;
                    
                    // get all images
                    this.getUserImages();
                }).catch(errors => { 
                    this.errors =  errors.response.data.errors;
                }).finally(() => {
                    this.isLoading = false;
                });
            },
        },
        created() { 
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            this.getUserImages(); 

            let modelObj = this;
            console.log("start user");
            Echo.channel('my-channel').listen('UserImageDownloaded', (e) => {
                    // console.log("start my-channel");
                    // console.log(e);
                    modelObj.getUserImages();
                    modelObj.snackbar = true;
                    modelObj.snackbarText = "Your image Uploaded Successfully";
            });
        }
    }
</script>