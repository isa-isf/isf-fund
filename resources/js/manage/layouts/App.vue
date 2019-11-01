<template>
    <div>
        <Layout>
            <Header>
                <Menu mode="horizontal" theme="dark" active-name="1">
                    <div class="layout-logo"></div>
                    <div class="layout-nav">
                    </div>
                </Menu>
            </Header>
            <router-view></router-view>
            <Footer class="layout-footer-center">socialism.org.tw</Footer>
        </Layout>
    </div>
</template>

<script>
export default {
  name: "App"
}
</script>
