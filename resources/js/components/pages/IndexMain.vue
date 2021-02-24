<template>  
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app >
            <v-list dense>
                <v-list-item link to="/dashboard">
                    <v-list-item-action> </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Dashboard</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/image">
                    <v-list-item-action> </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Image</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="#" @click="userLogout">
                    <v-list-item-action> </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
  
        <v-app-bar app color="indigo" dark >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>Application</v-toolbar-title>
        </v-app-bar>
  
        <v-main>
            <v-container >
                <v-row align="center" justify="center" >
                    <v-col class="text-center">
                        <router-view class="main-view" name="MainView"></router-view>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
      <v-footer color="indigo" app >
        <span class="white--text">&copy; {{ new Date().getFullYear() }}</span>
      </v-footer>
    </v-app> 
</template>

<script> 
    export default {
        name: "IndexMain",
        components: {
        },
        data: () => ({
            drawer: null,
            token: localStorage.getItem('token'), 
        }),
        created() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        },
        methods: {
            userLogout(){
                axios.post('/api/logout').then(res => { 
                    localStorage.removeItem('token');
                    this.$router.push('/login');
                });
            }, 
        }
    }
</script>