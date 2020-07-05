<template>
  <div class="container">
    <el-table
      :data="items"
      style="width: 100%"
      :row-class-name="highlightRow"
    >
      <el-table-column
        prop="sku"
        label="SKU"
        width="180"
      />
      <el-table-column
        prop="qty"
        label="Anzahl"
        width="100"
      />
      <el-table-column
        prop="name"
        label="Name"
      /></el-table>
  </div>
</template>

<script>
export default {
  name: 'Inventur',
  components: {},
  data() {
    return {
      items: [{
        sku: '2016-05-03',
        qty: 2,
        name: 'INOXSIGN N.30.E',
        direction: true,
      }, {
        sku: '2016-05-02',
        qty: 5,
        name: 'INOXSIGN N.12.E',
        direction: true,
      }, {
        sku: '2016-05-04',
        qty: 1,
        name: 'INOXSIGN N.10.E',
        direction: false,
      }, {
        sku: '2016-05-01',
        qty: 5,
        name: 'INOXSIGN N.03.E',
        direction: true,
      }],
    };
  },
  computed: {
  },
  methods: {
    highlightRow({ row, rowIndex }) {},
  },
};
</script>

<style rel="stylesheet/scss" lang="scss">
  .container {
    min-height: 100vh;
  }
  .el-table .out {
    background: #f9ebeb;
  }

  .el-table .in {
    background: #f0f9eb;
  }
</style>
