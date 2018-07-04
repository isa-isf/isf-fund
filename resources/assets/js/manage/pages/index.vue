<template>
    <Content class="content-wrapper">
        <Row>
            <Col span="16">
                <iTable :columns="columns" :data="data" :loading="loading" />
            </Col>
        </Row>
    </Content>
</template>

<script>
import axios from 'axios';
import formatter from 'number-format.js';

export default {
  name: 'index',
  data() {
    return {
      data: [],
      columns: [
        {
          title: '#',
          key: 'id',
          align: 'right',
          sortable: true,
          sortType: 'desc',
          width: 80,
        },
        {
          title: '聯絡資料',
          render: (h, params) => (
            <span>
              <strong>{ params.row.name }</strong><br />
              { params.row.phone }<br />
              { params.row.email }
            </span>
          ),
        },
        {
          title: '類型',
          render: (h, params) => (
            <span>{
              (() => {
                switch (params.row.type) {
                  case 'monthly':
                    return <span>月捐 - {params.row.count} 期</span>;
                  case 'one-time':
                    return <span>一次性</span>;
                }
              })()
            }</span>
          ),
          width: 120,
        },
        {
          title: '金額',
          render: (h, params) => (
            <span>{ formatter('$#,##0.TWD', params.row.amount) }</span>
          ),
          align: 'right',
          sortable: true,
          width: 100,
        },
        {
          title: '最近三筆授權',
          key: '',
          render: (h, params) => (
            <span>
              <ul>
                {
                  params
                    .row
                    .payments
                    .map(payment => (
                      <li>
                        { payment.created_at } -
                        { ({ 100: '未付款', 101: '成功', 999: '授權失敗', })[payment.status] }
                      </li>
                    ))
                }
              </ul>
            </span>
          ),
          width: 220,
        },
        {
          title: '時間',
          key: 'created_at',
          sortable: true,
          width: 180,
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
