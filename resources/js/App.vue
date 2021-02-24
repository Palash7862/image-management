<template>  
    <router-view></router-view>
</template>

<script>  
    export default {
        name: "App",
        components: { 
        },
        created() {
            console.log('created App');
        }
    }
</script>