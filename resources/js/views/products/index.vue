<template>
  <div class="container">
    <el-table
      :data="products"
      style="width: 100%"
    >
      <el-table-column
        label="Image"
        width="100"
      >
        <template slot-scope="scope">
          <el-image
            :src="scope.row.image"
            fit="cover"
          >
            <div slot="error" class="image-slot">
              <i class="el-icon-picture-outline" />
            </div>
          </el-image>
        </template>
      </el-table-column>
      <el-table-column
        prop="name"
        label="Name"
      />

      <el-table-column
        prop="barcode"
        label="Barcode"
        width="180"
      />

    </el-table>
    <el-pagination
      :current-page="current_page"
      layout="prev, pager, next"
      :page-count="last_page"
      @current-change="currentPage"
      @prev-click="prevPage"
      @next-click="nextPage"
    />
  </div>
</template>

<script>
export default {
  name: 'Products',
  components: {},
  data() {
    return {
      products: [],
      current_page: 1,
      last_page: null,
    };
  },
  created(){
    this.fetchProductPage(this.current_page);
  },
  methods: {
    updateProducts(data){
      this.current_page = data.current_page;
      this.last_page = data.last_page;
      this.products = data.data;
    },
    fetchProductPage(page){
      fetch('/api/products?page=' + page)
        .then(response => response.json())
        .then(data => this.updateProducts(data));
      this.current_page = page;
    },
    prevPage(){
      this.fetchProductPage(this.current_page - 1);
    },
    nextPage(){
      this.fetchProductPage(this.current_page + 1);
    },
    currentPage(val){
      this.fetchProductPage(val);
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss">
  .container {
    min-height: 100vh;
  }
  .el-image{
    box-shadow: 0 2px 6px -2px rgba(0, 0, 0, 0.1);
    width: 50px;
    height: 50px;
    margin: 5px 1em 5px 0;
  }
  .image-slot{
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    background: #f5f7fa;
    color: #909399;
  }
  .el-pagination{
    display: flex;
    margin: 50px 0;
    justify-content: center;
    align-items: center;
  }
</style>
