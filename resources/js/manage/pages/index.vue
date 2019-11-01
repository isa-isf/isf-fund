<template>
    <Content class="content-wrapper">
        <Row>
            <Col span="16" class="ph2">
                <iTable
                    stripe
                    :columns="columns"
                    :data="data"
                    :loading="loading"
                    @on-row-click="showData"
                />
            </Col>
            <Col span="8" class="ph2">
                <Card v-if="donation.id">
                    <p slot="title">
                        #{{ donation.id }}
                        {{ donation.name }}
                    </p>
                    <iForm>
                        <FormItem label="地址">
                            <p>{{ donation.address }}</p>
                        </FormItem>
                        <FormItem label="留言">
                            <p>{{ donation.message }}</p>
                        </FormItem>
                        <FormItem label="付款歷史">
                            <ul class="pl5">
                                <li v-for="payment in donation.payments" :key="payment.id">
                                    {{ payment.created_at }} -
                                    {{
                                      ({ 100: '未付款', 101: '成功', 999: '授權失敗' })[payment.status]
                                    }}
                                </li>
                            </ul>
                        </FormItem>
                        <FormItem label="操作">
                            <ButtonGroup>
                                <iButton
                                    type="warning"
                                    icon="ios-archive"
                                    @click="archive(donation.id)"
                                    :loading="loading"
                                >
                                    封存
                                </iButton>
                            </ButtonGroup>
                        </FormItem>
                    </iForm>
                </Card>
            </Col>
        </Row>
    </Content>
</template>

<script>
import axios from 'axios';
import formatter from 'number-format.js';
import clone from '../../utils/clone';

export default {
  name: 'index',
  data() {
    return {
      donation: {
        id: null,
      },

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
                    .slice(-3)
                    .reverse()
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
    showData(donation) {
      this.$set(this, 'donation', clone(donation));
    },
    archive(id) {
      this.$Modal.confirm({
        title: '確定要封存？',
        content: '確定要將所選的這筆項目封存嗎？',
        loading: true,
        onOk: async () => {
          this.loading = true;
          await axios.put(`/_/donations/${id}/archive`);
          await this.fetchData();
          this.$Modal.remove();
          this.$set(this, 'donation', { auto: null });
          this.loading = false;
        }
      });
    },
  },
  mounted() {
    this.fetchData();
  },
}
</script>
