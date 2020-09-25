<template>
  <div class="container">
    <el-card>
      <el-table
        :data="items"
        style="width: 100%"
      >
        <el-table-column
          label="Barcode"
          width="180"
          align="center"
          class-name="barcode"
          label-class-name="barcode-label"
        >
          <template slot-scope="scope">
            {{ scope.row[0].product.barcode }}
          </template>
        </el-table-column>
        <el-table-column
          label="Anzahl"
          width="100"
          align="center"
        >
          <template slot-scope="scope">
            {{ scope.row.length > 1 ? scope.row.reduce((p, c) => (isNaN(p.quantity) ? p + c.quantity: p.quantity + c.quantity)) : scope.row[0].quantity }}
          </template>
        </el-table-column>
        <el-table-column
          label="Produkt"
        >
          <template slot-scope="scope">
            <div class="product-column">
              <el-image
                :src="'https://api.rethumb.com/v1/square/100/'+scope.row[0].product.image"
                fit="cover"
              >
                <div slot="error" class="image-slot">
                  <i class="el-icon-picture-outline" />
                </div>
              </el-image>
              {{ scope.row[0].product.name }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Lager">
          <template slot-scope="scope">
            <el-table
              :data="scope.row"
              style="width: 100%"
              size="mini"
              :show-header="false"
              row-class-name="warehouse_table_row"
              class="warehouse_table"
            >
              <el-table-column
                prop="warehouse.name"
                width="150"
              />
              <el-table-column
                prop="quantity"
                width="50"
              />
            </el-table>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';

const stocksResource = new Resource('stocks');

export default {
  name: 'Inventur',
  data() {
    return {
      search: '',
      items: [],
    };
  },
  computed: {
    sorted_items() {
      return this.items.slice(0).sort(function(a, b) {
        if (a[0].product.label_text < b[0].product.label_text) {
          return -1;
        }
        if (a[0].product.label_text > b[0].product.label_text) {
          return 1;
        }
        return 0;
      });
    },
    filtered_items() {
      return this.sorted_items.filter(item => {
        return item[0].product.label_text.toLowerCase().indexOf(this.search.toLowerCase()) > -1 ||
            item[0].product.barcode.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
      });
    },
  },
  created(){
    stocksResource.list()
      .then(data => (this.items = Object.values(data)));
  },
};
</script>

<style lang="scss" scoped>
  .container /deep/ {
    min-height: 100vh;
    padding: 20px;
    padding-bottom: 100px;
      background: #FAFAFA;

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
    .product-column{
      display: flex;
      align-items: center;
    }
    .barcode{
      font-family: 'Courier New', Courier, monospace;
      font-weight: 600;
    }
    .barcode-label{
      font-family: inherit;
      font-weight: inherit;
    }
    .warehouse_table,
    .warehouse_table table,
    .warehouse_table_row,
    .warehouse_table_row td,
    .warehouse_table:hover,
    .warehouse_table_row:hover
    .warehouse_table_row:hover td{
      background: inherit!important;
      background-color: inherit!important;
      border:none!important;
    }

    .warehouse_table::before{
      content:"";
      display:none;
    }
  }
</style>
