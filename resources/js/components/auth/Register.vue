<template> 
    <v-app id="inspire"> 
        <v-main>
            <v-container class="fill-height" fluid >
            <v-row align="center" justify="center" >
                <v-col cols="12"  sm="8" md="4" >
                    <v-card class="elevation-12" :loading="isLoading">
                        <v-toolbar color="primary" dark flat >
                            <v-toolbar-title>Register form</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn href="#" icon large target="_blank" v-on="on" >
                                        <v-icon>mdi-code-tags</v-icon>
                                    </v-btn>
                                </template>
                                <span>Source</span>
                            </v-tooltip>
                        </v-toolbar>
                        <v-card-text>
                            <v-form ref="form">
                                <v-text-field label="Full Name" name="name" prepend-icon="mdi-account" type="text" v-model="form.name" :error-messages="errors.name"> 
                                </v-text-field>

                                <v-text-field label="Email" name="email" prepend-icon="mdi-account" type="text" v-model="form.email" :error-messages="errors.email"></v-text-field>

                                <v-text-field id="password" label="Password" name="password" prepend-icon="mdi-lock" type="password" v-model="form.password" :error-messages="errors.password"> 
                                </v-text-field>
                                <v-text-field id="password_confirmation" label="Confirm Password" name="password_confirmation" prepend-icon="mdi-lock" type="password" v-model="form.password_confirmation" :error-messages="errors.password_confirmation">
                                </v-text-field>
                        </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <router-link class="ml-3" to="/login">Already have a account ?</router-link>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="userRegister">Register</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
            </v-container>
        </v-main> 
        <v-snackbar v-model="snackbar" top color="success" timeout="-1">
            {{ snackbarText }} 
            <template v-slot:action="{ attrs }">
                <v-btn color="primary" text v-bind="attrs" @click="goLogin"> Login </v-btn>
            </template>
        </v-snackbar>
    </v-app> 
</template>

<script> 
    export default {
        name: "Register",
        components: {
        },
        data() {
            return {
                form: {
                    name: '',
                    email:'',
                    password: '',
                    password_confirmation: '',  
                },
                errors: {}, 
                isLoading: false, 
                snackbar: false, 
                snackbarText: 'Successfully'
            }
        },  
        created() {
            if(localStorage.getItem('token')){
                this.$router.push('/dashboard');
            }
        }, 
        methods: { 
            userRegister(){
                this.isLoading = 'red';
                axios.post('/api/register', this.form).then(res => { 
                    this.snackbar = true;
                    this.snackbarText = res.data.message;
                    this.$refs.form.reset();
                    this.$refs.form.resetValidation();
                }).catch(errors => { 
                    this.errors =  errors.response.data.errors;
                }).finally(() => {
                    this.isLoading = false;
                });
            },
            goLogin(){
                this.snackbar = false;
                setTimeout(() => { 
                    // console.log(this.$router);
                    this.$router.push('/login');
                }, 300)
            }
        }
    }
</script>