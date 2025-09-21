<template>
  <v-container fluid class="pa-4">
    <v-card elevation="6" class="mx-auto my-6 rounded-lg" max-width="1200">
      <v-card-title class="d-flex align-center">
        <span class="text-h5 font-weight-bold">WordPress Blog Management</span>
        <v-spacer></v-spacer>
        <v-btn color="primary" dark @click="refreshPosts">
          <v-icon left>mdi-refresh</v-icon> Refresh Posts
        </v-btn>
        <v-btn color="green darken-2" dark class="ml-2" @click="openCreateModal">
          <v-icon left>mdi-plus</v-icon> Add New Post
        </v-btn>
      </v-card-title>

      <v-card-subtitle class="pb-4">
        Manage all your WordPress blog posts. Create, edit, view, or delete posts.
      </v-card-subtitle>

      <v-data-table
        :headers="headers"
        :items="posts"
        :items-per-page="itemsPerPage"
        :page.sync="page"
        item-key="id"
        class="elevation-1 rounded-lg"
        dense
        :loading="loading"
      >
        <template v-slot:top>
          <v-toolbar flat color="white">
            <v-toolbar-title>WordPress Posts</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search Posts"
              single-line
              hide-details
              @input="fetchPosts"
            ></v-text-field>
          </v-toolbar>
        </template>

        <template v-slot:item.status="{ item }">
          <v-chip
            :color="item.status === 'publish' ? 'green lighten-1' : 'grey lighten-1'"
            dark small
          >
            {{ item.status === 'publish' ? 'Published' : 'Draft' }}
          </v-chip>
        </template>

        <template v-slot:item.priority="{ item }">
          <v-text-field
            type="number"
            v-model.number="item.priority"
            @change="updatePriority(item)"
            class="ma-0 pa-0"
            style="width: 60px"
          ></v-text-field>
        </template>

        <template v-slot:item.actions="{ item }">
          <v-btn small color="blue darken-2" dark class="mr-2" @click="openEditModal(item)">
            <v-icon left>mdi-pencil</v-icon>Edit
          </v-btn>
          <v-btn small color="red darken-2" dark class="mr-2" @click="deletePost(item)">
            <v-icon left>mdi-delete</v-icon>Delete
          </v-btn>
          <v-btn small color="grey darken-1" dark @click="viewContent(item)">
            <v-icon left>mdi-eye</v-icon>View
          </v-btn>
        </template>
      </v-data-table>
    </v-card>

    <!-- Create/Edit Modal -->
    <v-dialog v-model="showModal" max-width="600">
      <v-card>
        <v-card-title>{{ modalTitle }}</v-card-title>
        <v-card-text>
          <v-text-field label="Title" v-model="form.title"></v-text-field>
          <v-select
            label="Status"
            :items="['publish','draft']"
            v-model="form.status"
          ></v-select>
          <v-textarea label="Content" v-model="form.content"></v-textarea>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-2" dark @click="savePost">Save</v-btn>
          <v-btn color="grey" dark @click="showModal=false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      posts: [],
      loading: false,
      search: '',
      page: 1,
      itemsPerPage: 5,
      showModal: false,
      modalTitle: '',
      editingPost: null,
      form: { title: '', status: 'draft', content: '' },
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Title', value: 'title.rendered' },
        { text: 'Date', value: 'date' },
        { text: 'Status', value: 'status' },
        { text: 'Priority', value: 'priority' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
    };
  },
  mounted() { this.fetchPosts(); },
  methods: {
    fetchPosts() {
      this.loading = true;
      axios.get('/api/posts', { params: { search: this.search } })
        .then(res => {
          this.posts = res.data.map(post => ({
            id: post.id,
            title: post.title,
            date: post.date,
            content: post.content.rendered ?? post.content,
            priority: post.priority ?? 0,
            status: post.status
          }));
          this.loading = false;
        })
        .catch(err => {
          console.error(err.response?.data || err);
          this.loading = false;
          alert('Failed to fetch posts.');
        });
    },
    refreshPosts() { this.fetchPosts(); },

    openCreateModal() {
      this.modalTitle = 'Create New Post';
      this.editingPost = null;
      this.form = { title: '', status: 'draft', content: '' };
      this.showModal = true;
    },

    openEditModal(post) {
      this.modalTitle = 'Edit Post';
      this.editingPost = post;
      this.form = {
        title: post.title.rendered ?? post.title,
        status: post.status,
        content: post.content
      };
      this.showModal = true;
    },

    savePost() {
      if (!this.form.title || !this.form.content) {
        return alert('Title and Content are required.');
      }

      this.loading = true;
      const url = this.editingPost?.id ? `/api/posts/${this.editingPost.id}` : '/api/posts';
      const method = this.editingPost?.id ? 'put' : 'post';

      axios[method](url, {
        title: this.form.title,
        content: this.form.content,
        status: this.form.status
      })
        .then(res => {
          if (res.status === 200 || res.status === 201) {
            this.showModal = false;
            this.fetchPosts();
            alert('Post saved successfully!');
          } else {
            alert(`Unexpected response: ${res.status}`);
          }
        })
        .catch(err => {
          console.error(err.response?.data || err);
          alert('Failed to save post.');
        })
        .finally(() => { this.loading = false; });
    },

    deletePost(post) {
      if (!confirm(`Delete "${post.title.rendered ?? post.title}"?`)) return;
      axios.delete(`/api/posts/${post.id}`)
        .then(() => {
          this.posts = this.posts.filter(p => p.id !== post.id);
        })
        .catch(err => {
          console.error(err.response?.data || err);
          alert('Failed to delete post.');
        });
    },

    viewContent(post) {
      const win = window.open('', '_blank');
      win.document.write(`<h2>${post.title.rendered ?? post.title}</h2>${post.content}`);
    },

    updatePriority(post) {
      axios.post(`/api/posts/${post.id}/priority`, { priority: post.priority })
        .then(res => {
          if (!res.data.ok) alert('Failed to update priority.');
        })
        .catch(err => {
          console.error(err.response?.data || err);
          alert('Failed to update priority.');
        });
    }
  }
};
</script>

<style scoped>
.v-data-table { border-radius: 12px; }
.v-card-title { border-bottom: 1px solid #eee; }
.v-card-subtitle { font-size: 0.9rem; color: #666; }
</style>
