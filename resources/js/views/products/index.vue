<template>
  <div class="container">
    <el-card>
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
              :src="'https://api.rethumb.com/v1/square/100/'+scope.row[0].product.image"
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
          prop="label_text"
          label="Label"
          width="200"
        />
        <el-table-column
          prop="barcode"
          label="Barcode"
          width="180"
          class-name="barcode"
          label-class-name="barcode-label"
        />

        <el-table-column
          prop="sku"
          label="SKU"
          width="180"
          class-name="barcode"
          label-class-name="barcode-label"
        />
        <el-table-column
          prop="price"
          label="Preis"
          width="100"
        >
          <template slot-scope="scope">
            {{ scope.row.price.replace(".", ",") }} €
          </template>
        </el-table-column>
      </el-table>
    </el-card>
    <div style="position: absolute;bottom: 0;left: 0;right: 0;">
      <el-card>
        <el-pagination
          :current-page="current_page"
          layout="prev, pager, next"
          :page-count="last_page"
          @current-change="currentPage"
          @prev-click="prevPage"
          @next-click="nextPage"
        />
      </el-card>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';

const productResource = new Resource('products');

export default {
  name: 'Products',
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
    min-height: calc( 100vh - 150px);
    padding-bottom: 100px;
      background: #FAFAFA;
      position: relative;

    .el-card{
      margin: 20px;
    }
    .barcode{
      font-family: 'Courier New', Courier, monospace;
      font-weight: 600;
    }
    .barcode-label{
      font-family: inherit;
      font-weight: inherit;
    }
    .el-image{
      box-shadow: 0 2px 6px -2px rgba(0, 0, 0, 0.1);
      width: 72px;
      height: 72px;
      margin: 5px 1em 5px 0;
      border-radius: 5px;
    }

    .image-slot{
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 3em;
      width: 100%;
      height: 100%;
      background: #f5f7fa;
      color: #909399;
    }

    .el-pagination{
      display: flex;
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
