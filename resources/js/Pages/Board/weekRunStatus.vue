<template>

    <ul class="min-w-full steps">
        <li v-for="(attend,i) in attends" v-bind:key="i"  :data-content="attend.run" class="step step-neutral">      {{ attend.date }}</li>
    </ul>


</template>
<script>
    export default ({
        data: () => ({
             user_id: document.head.querySelector('meta[name="user-id"]').content,
             attends: [],
        }),
        mounted() {
            axios.get(`/api/user/${this.user_id}/attend/week`)
            .then(response=> {
                
                response.data.data.map(element=>{
                    console.log(element)
                    if(element.desc_value == "지각") {
                        this.attends.push(element);
                    }
                })
            })
        },
    })
</script>
