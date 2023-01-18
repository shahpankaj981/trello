<template>
    <div class="p-5">
        <div class="row pb-5">
          <div class="col-md-4">
            <input type="text" placeholder="Column name.." v-model="new_column" class="form-control" />
          </div>
          <div class="col-md 2">
            <button type="button" class="btn btn-block btn-success" @click="addColumn">Add Column</button>
          </div>
        </div>
        <div class="row no-wrap">
          <div class="col-3" v-for="column in columns">
            <div class="row">
              <h3 class="col-md-9" v-text="column.name"></h3>
              <div class="col-md-3">
                <button class="btn btn-secondary btn-danger" @click="deleteColumn(column.id)">Delete</button>
              </div>
            </div>
        <draggable
          id={column.id}
          data-source="juju"
          :list="list"
          class="list-group"
          draggable=".item"
          group="a"
        >
          <div
            class="list-group-item item"
            v-for="element in list"
            :key="element.name"
          >
            {{ element.name }}
          </div>
  
          <div
            slot="header"
            class="btn-group list-group-item"
            role="group"
            aria-label="Basic example"
          >
            <button class="btn btn-secondary btn-block" @click="add">Add Card</button>
          </div>
        </draggable>
      </div>

    </div>
  </div>
  </template>
  
  <script>
  import axios from 'axios';
  import draggable from "vuedraggable";
  import {axiosApiInstance} from '../app';

  let id = 1;
  export default {
    display: "Two list header slot",
    order: 14,
    components: {
      draggable
    },
    data() {
      return {
        columns: [],
        new_column: "",
        list: [
          { name: "John 1", id: 0 },
          { name: "Joao 2", id: 1 },
          { name: "Jean 3", id: 2 }
        ],
        list2: [{ name: "Jonny 4", id: 3 }, { name: "Guisepe 5", id: 4 }]
      };
    },
    mounted() {
      this.fetchColumns();
    },
    methods: {
      add: function() {
        this.list.push({ name: "Juan " + id, id: id++ });
      },
      replace: function() {
        this.list = [{ name: "Edgard", id: id++ }];
      },
      add2: function() {
        this.list2.push({ name: "Juan " + id, id: id++ });
      },
      replace2: function() {
        this.list2 = [{ name: "Edgard", id: id++ }];
      },
      fetchColumns : function() {
        // const result = 
        axiosApiInstance.get(`/api/columns`).then(res => {
          console.log(res)
          this.columns = res.data.columns;
        })
      },
      addColumn() {
        if(this.new_column) {
          axios.post('/api/columns', {name: this.new_column})
          .then(res => {
            alert("Column added succesfully");
            this.fetchColumns();
            this.new_column = '';
          })
        } else {
          alert("Column Name Required field")
        }
      },
      deleteColumn(id) {
        if(confirm("Are you sure you want to delte the column?")) {
          axios.delete(`/api/columns/${id}`)
          .then(res => {
            alert("Column deleted succesfully");
            this.fetchColumns();
          })
        }
      }
    }
  };
  </script>
  <style scoped></style>