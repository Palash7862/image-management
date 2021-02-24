<template> 
    <v-app id="inspire"> 
        <v-main>
            <v-container class="fill-height" fluid >
            <v-row align="center" justify="center" >
                <v-col cols="12"  sm="8" md="4" >
                    <v-card class="elevation-12" :loading="isLoading">
                        <v-toolbar color="primary" dark flat >
                            <v-toolbar-title>Login form</v-toolbar-title>
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
                                <v-text-field label="Login" name="login" prepend-icon="mdi-account" type="text" v-model="form.email" :error-messages="errors.email"></v-text-field>
                                <v-text-field id="password" label="Password" name="password" prepend-icon="mdi-lock" type="password" v-model="form.password" :error-messages="errors.password">
                                </v-text-field>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <router-link class="ml-3" to="/register">Create new account ?</router-link>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="userLogin">Login</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
            </v-container>
        </v-main> 
        <v-snackbar v-model="snackbar" top color="success" timeout="-1">
            {{ snackbarText }} 
            <template v-slot:action="{ attrs }">
                <v-btn color="primary" text v-bind="attrs" @click="userLogin"> Login </v-btn>
            </template>
        </v-snackbar>
    </v-app> 
</template>

<script> 
    export default {
        name: "Login",
        components: {}, 
        created() {
            if(localStorage.getItem('token')){
                this.$router.push('/dashboard');
            }
        }, 
        data() {
            return {
                form: { 
                    email:'',
                    password: '', 
                    device_name: 'browser',
                },
                errors: {}, 
                isLoading: false, 
                snackbar: false, 
                snackbarText: 'Successfully'
            }
        }, 
        methods: { 
            userLogin(){
                this.isLoading = 'red';
                axios.post('/api/login', this.form).then(res => { 
                    localStorage.setItem('token', res.data.token)
                    this.snackbar = true;
                    this.snackbarText = res.data.message;
                    this.$refs.form.reset();
                    this.$refs.form.resetValidation();
                    this.goHome();
                }).catch(errors => { 
                    this.errors =  errors.response.data.errors;
                }).finally(() => {
                    this.isLoading = false;
                });
            },
            goHome(){
                this.snackbar = false;
                setTimeout(() => {  
                    this.$router.push('/dashboard');
                }, 1000)
            }
        }
    }
</script>