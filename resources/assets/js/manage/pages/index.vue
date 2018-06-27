<template>
    <Content class="content-wrapper">
        <Table :columns="columns" :data="data" :loading="loading" />
    </Content>
</template>

<script>
import axios from 'axios';

export default {
  name: 'index',
  data() {
    return {
      data: [],
      columns: [
        {
          title: '#',
          key: 'id',
        },
        {
          title: '姓名',
          key: 'name',
        },
        {
          title: '電話',
          key: 'phone',
        },
        {
          title: 'E-Mail',
          key: 'email',
        },
        {
          title: '地址',
          key: 'address',
        },
        {
          title: '類型',
          key: 'type',
        },
        {
          title: '期數',
          key: 'count',
        },
        {
          title: '金額',
          key: 'amount',
        },
        {
          title: '附言',
          key: 'message',
        },
        {
          title: '時間',
          key: 'created_at',
        },
      ],
      loading: false,
    };
  },
  methods: {
    async fetchData() {
      this.loading = true;
      await axios
        .get('/_/donations')
        .then((resp) => {
          this.$set(this, 'data', resp.data);
        });
      this.loading = false;
    },
  },
  mounted() {
    this.fetchData();
  },
}
</script>
