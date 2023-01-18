<template>
    <modal name="card-modal" @closed="handleClose">
        <h4 class="text-center mt-2">Card Details</h4>

        <form @submit.prevent="handleSubmit">
            <div class="row p-4">
            <div class="col-md-12 form-group">
                <label class="form-label">Title *</label>
                <input class="form-control" name="name" v-model="values.name" required />
            </div>
            <div class="col-md-12 form-group">
                <label  class="form-label">Description</label>
                <textarea class="form-control" name="description" v-model="values.description"></textarea>
            </div>
            <div class="col-md-12 form-group mt-3">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </div>
        </form>
    </modal>
</template>

<script>
import {axiosApiInstance} from '../app';

export default {
    props: ['showModal', 'columnId', 'card'],
    data() {
        return {
            show: false,
            values: {
                name: '',
                description: ''
            }
        }
    },
    methods: {
        handleClose() {
            this.$emit('close')
        },
        handleSubmit() {
            console.log(this.$props.card.id);
            if(this.$props.card.id) {
                axiosApiInstance.put(`/api/card/${this.$props.card.id}`, this.values)
                .then(res => {
                    console.log('card updated successfully');
                    this.$emit('cardUpserted');
                })
            } else {
                let data = {...this.values, column_id: this.$props.columnId};

                axiosApiInstance.post(`/api/card`, data)
                .then(res => {
                    console.log('card inserted successfully');
                    this.$emit('cardUpserted');
                })
            }
            this.handleClose();
        }
    },
    mounted() {
        console.log(this.$props)
        this.show = this.$props.showModal;
        this.values = this.$props.card;
    },
    watch: {
        showModal(value) {
            if(value) {
                this.$modal.show('card-modal');
            } else {
                this.$modal.hide('card-modal');
            }
        },
        card(value) {
            this.values = value;
        }
        
    }
}
</script>