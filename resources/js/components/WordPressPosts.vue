<template>
  <div>
    <h1>WordPress Posts</h1>

    <div v-if="loading">Loading posts...</div>
    <div v-if="error" style="color:red">{{ error }}</div>

    <ul v-if="posts.length">
      <li v-for="post in posts" :key="post.id" style="margin-bottom: 20px;">
        <strong>{{ post.title.rendered }}</strong> (Priority: {{ post.priority }})<br>
        <div v-html="post.content.rendered"></div>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'WordPressPosts',
  data() {
    return {
      posts: [],
      loading: true,
      error: null
    };
  },
  mounted() {
    axios.get('/api/posts')
      .then(res => {
        this.posts = res.data;
        this.loading = false;
      })
      .catch(err => {
        this.error = err.response?.data?.error || 'Failed to fetch posts';
        this.loading = false;
      });
  }
}
</script>
