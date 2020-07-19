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
          <ImageUpload
            :image.sync="scope.row.image"
            :action.sync="'/api/products/'+scope.row.id"
          />
        </template>
      </el-table-column>
      <el-table-column
        prop="name"
        label="Name"
        width="400"
      >
        <template slot-scope="scope">
          <el-input v-model="scope.row.name" placeholder="Product Name" @blur="update(scope.row)" />
        </template></el-table-column>
      <el-table-column
        prop="barcode"
        label="Barcode"
        width="180"
      >
        <template slot-scope="scope">
          <el-input v-model="scope.row.barcode" placeholder="Product Barcode" @blur="update(scope.row)" />
        </template>

      </el-table-column></el-table>
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
import Resource from '@/api/resource';
import ImageUpload from '@/components/Upload/ImageUpload.vue';

const productResource = new Resource('products');

export default {
  name: 'Products',
  components: { ImageUpload },
  data() {
    return {
      products: [],
      current_page: 1,
      last_page: null,
    };
  },
  created(){
    this.fetchProductPage(this.current_page);
    document.addEventListener('keydown', this.logKeys);
  },
  methods: {
    logKeys(e){
      if (event.keyCode === 37) {
        this.prevPage();
      }
      if (event.keyCode === 39) {
        this.nextPage();
      }
    },
    async fetchProductPage(page){
      const data = await productResource.list({ 'page': page });

      this.current_page = data.current_page;
      this.last_page = data.last_page;
      this.products = data.data;

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
    async update(product){
      productResource.update(product.id, product)
        .then(() => {
          this.$message({
            message: 'Product updated',
            type: 'success',
          });
        });
    },
  },
};
</script>

<style lang="scss" scoped>
  .container /deep/ {
    min-height: 100vh;
    padding-bottom: 100px;

    .el-pagination{
      display: flex;
      margin: 50px 0;
      justify-content: center;
      align-items: center;
    }
    .el-input--medium .el-input__inner{
      border: 1px solid rgba(0,101,202,0);
      background: none;
    }
    .el-input--medium .el-input__inner:focus,
    .el-input--medium .el-input__inner:hover{

      border: 1px solid rgba(0,101,202,1);
    }
  }
</style>
