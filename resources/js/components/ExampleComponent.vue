<template>
    <div class="h-screen w-auto flex justify-center items-center">
        <div class="container">
            <h4 class="text-center">
            Arrange Tasks
            </h4>
            <div class="container flex justify-around">
                <div></div>
                <div>Day</div>
                <div>Type</div>
                <div>Task/Story Name</div>
            </div>
            <div v-if="tasks !== ''">
                <draggable 
                    handle=".handle"
                    animation=150
                    :list="tasks"
                >
                    <div v-for="task in tasks" :key="task.id" class="bg-grey-light my-4 px-4 py-8">
                        <div class="flex justify-around">
                            <i class="fas fa-bars text-4xl handle"></i>
                            <div class="w-1/4 text-center">
                                {{ task.day }}
                            </div>
                            <div class="w-1/4" v-text="task.type">
                            </div>
                            <div class="w-1/4">
                                <input type="text" v-model="task.name" :disabled="task.type == 'Story'" :class="task.type == 'Story' ? 'bg-grey-light':''">
                            </div>
                        </div>
                    </div>
                </draggable>
            </div>
            <div class="flex justify-center">
                <button class="bg-blue rounded shadow text-white px-8 py-2" @click="submitTasks()">submit</button>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from "vuedraggable";
    export default {
        components: { draggable },
        created() {
            this.getTasks();
        },
        data(){
            return {
                tasks: '',
            }
        },
        methods: {
            getTasks(){
                axios.get('/api/tasks')
                    .then(tasks => this.tasks = tasks.data);
            },
            submitTasks(){
                console.log(this.tasks);
                axios.post('/api/update', {"tasks":this.tasks})
                    .then(() => {
                        this.getTasks();
                    });
            }
        }
    }
</script>
