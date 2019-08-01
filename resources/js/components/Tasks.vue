<template>
    <div class="h-screen w-auto flex justify-center items-center">
        <div class="container flex justify-around">
            <!-- Arrange Tasks --> 
            <div class="w-1/2">
                <h4 class="text-center">
                Arrange Tasks
                </h4>
                <div class="container flex">
                    <div class="w-1/5"></div>
                    <div class="w-1/5 ml-5">Day</div>
                    <div class="w-1/5">Type</div>
                    <div class="w-1/3 text-center ml-5">Task/Story Name</div>
                </div>
                <div v-if="tasks !== ''">
                    <draggable 
                        handle=".handle"
                        animation=150
                        @end="change"
                    >
                        <div v-for="task in tasks" :key="task.id" class="bg-grey-lighter my-4 px-4 py-8">
                            <div class="flex justify-around">
                                <i class="fas fa-bars text-4xl handle"></i>
                                <div class="w-1/4 text-center" v-text="task.day"></div>
                                <div class="w-1/4" v-text="task.type"></div>
                                <div class="w-1/4 text-center">
                                    <input type="text" v-model="task.name" :disabled="task.type === 'Story'" :class="task.type === 'Story' ? 'bg-grey-lighter':''">
                                </div>
                            </div>
                        </div>
                    </draggable>
                </div>
                <div class="flex justify-center">
                    <button class="bg-blue rounded shadow text-white px-8 py-2" @click="submitTasks()">submit</button>
                </div>
            </div>
            <!-- Arrange Tasks --> 
            <div class="w-1/4">
                <h4 class="text-center">
                    Preview
                </h4>
                <table width='100%'>
                    <tr class="bg-table-blue text-white text-small">
                        <th class="text-center w-1/2">Absolute Day</th>
                        <th class="text-center w-1/2">Name</th> 
                    </tr>
                    <tr v-for="task in formatTasks" :key="task.id">
                        <td class="text-center" v-text="task.absolute_day"></td>
                        <td class="text-center" v-text="task.name"></td> 
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
table, th, td {
    border: 0.1px solid black;
    border-collapse: collapse;

}
</style>


<script>
import draggable from "vuedraggable";
export default {
    components: { 
        draggable 
    },
    created() {
        this.getTasks();
    },
    data(){
        return {
            tasks: '',
            id: 1,
        }
    },
    methods: {
        getTasks(){
            axios.get('/api/tasks', {params:{id: this.id}})
                .then(tasks => this.tasks = tasks.data)
        },
        submitTasks(){
            axios.post('/api/update', {tasks: this.tasks})
                .then(() => this.getTasks());
        },
        change(evt){
            let newDays = this.tasks[evt.newIndex].story === '' ? 0 : this.tasks[evt.newIndex].story.take_days -1 ;
            
            this.tasks[evt.oldIndex].absolute_day = evt.newIndex + 1 + newDays;
            this.tasks[evt.newIndex].absolute_day = evt.oldIndex + 1;
            this.tasks.sort(function(a, b){
                return parseFloat(a.absolute_day) - parseFloat(b.absolute_day);
            });

            this.tasks.map(function(task){
                if(task.story === ''){
                    task.day = task.absolute_day;
                    return task;
                }
                task.day = `${task.absolute_day} - ${task.absolute_day + task.story.take_days - 1}`;
            });
        }
    },
    computed: {
        formatTasks(){
            let format = []
            for(let i = 0; i < this.tasks.length; i++){
                if(this.tasks[i].story === ''){
                    format.push(this.tasks[i]);
                }
                if(this.tasks[i].story !== ''){
                    let absolute_day = this.tasks[i].absolute_day;
                    let days = this.tasks[i].story.take_days;
                    for(let j = 0; j < days; j++){
                        format.push({
                            'absolute_day': absolute_day + this.tasks[i].story.daily_tasks_lisk[j].relative_day - 1,
                            'name': this.tasks[i].story.daily_tasks_lisk[j].task_name
                        });
                    }
                }
            }
            return format;
        }
    }
}
</script>
