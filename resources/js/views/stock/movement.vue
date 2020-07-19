<template>
  <div class="container">
    <el-table
      id="items-table"
      :data="items"
      style="width: 100%"
      :row-class-name="highlightRow"
      empty-text="Einlager oder Auslagern wählen und anfangen zu scannen."
    >
      <el-table-column
        width="50"
        align="center"
      >
        <template slot-scope="scope">
          <i v-if="scope.row.qty > 0" class="el-icon-download" />
          <i v-else class="el-icon-upload2" />
        </template>
      </el-table-column>
      <el-table-column
        prop="qty"
        label="Anzahl"
        width="180"
        align="center"
        sortable
      >
        <template slot-scope="scope">
          <el-input-number
            v-model="scope.row.qty"
            size="small"
          />
        </template>
      </el-table-column>

      <el-table-column
        prop="barcode"
        label="Barcode"
        width="180"
        align="center"
        class-name="barcode"
        label-class-name="barcode-label"
      />
      <el-table-column
        label="Produkt"
      >
        <template slot-scope="scope">
          <div class="product-column">
            <el-image
              :src="scope.row.image"
              fit="cover"
            >
              <div slot="error" class="image-slot">
                <i class="el-icon-picture-outline" />
              </div>
            </el-image>
            {{ scope.row.name }}
          </div>
        </template>
      </el-table-column>

      <el-table-column
        prop="action"
        label="Action"
        width="200"
        align="right"
      >
        <template slot-scope="scope">
          <el-button v-if="scope.row.qty > 0" icon="el-icon-upload2" type="danger" circle @click="scope.row.qty *= -1" />
          <el-button v-else icon="el-icon-download" type="success" circle @click="scope.row.qty *= -1" />
          <el-button
            type="danger"
            icon="el-icon-delete"
            circle
            @click="deleteItem(scope.$index, scope.row)"
          />
        </template>
      </el-table-column></el-table>
    <div class="toolbar">
      <el-input id="barcode" v-model="barcode" placeholder="Barcode" size="medium" style="width:10em;margin-right:1em" />
      <div style="display:inline-block;">
        <i v-if="direction" class="el-icon-download" />
        <i v-else class="el-icon-upload2" />
        <div style="display:inline-block;width:80px;text-align:center;">{{ direction ? 'Einlagern' : 'Auslagern' }}</div>
      </div>

      <el-switch
        v-model="direction"
        active-color="#13ce66"
        inactive-color="#ff4949"
      />
      <el-button icon="el-icon-check" type="success" style="margin-left:2em" @click="submitStock">Speichern</el-button>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';

const productResource = new Resource('products/barcode');
const stocksResource = new Resource('stocks');

export default {
  name: 'Movement',
  data() {
    return {
      barcode: '',
      direction: true,
      items: [],
    };
  },
  computed: {
    stock(){
      const stock = [];

      this.items.forEach(function(item){
        stock.push({
          product_id: item.product_id,
          warehouse_id: 1, // TODO: make it configurable
          quantity: item.qty,
        });
      });

      return stock;
    },
  },
  beforeRouteLeave(to, from, next) {
    if (this.items.length > 0){
      this.$confirm('Lageränderungen sind nicht gespeichert!', 'Verlassen?', {
        confirmButtonText: 'verlassen',
        confirmButtonClass: 'el-button--danger is-plain',
        cancelButtonText: 'abbrechen',
        type: 'error',
      }).then(() => {
        next();
      }).catch(action => {
        next(false);
      });
    } else {
      next();
    }
  },
  created(){
    document.addEventListener('keydown', this.logKeys);
  },
  methods: {
    highlightRow({ row, rowIndex }) {
      if (row.qty > 0) {
        return 'in';
      }
      if (row.qty < 0) {
        return 'out';
      }
      return '';
    },
    deleteItem(index, row) {
      this.$confirm('Wirklich löschen?', 'Element löschen', {
        confirmButtonText: 'löschen',
        confirmButtonClass: 'el-button--danger is-plain',
        cancelButtonText: 'abbrechen',
        type: 'error',
      }).then(() => {
        this.$delete(this.items, index);
        this.$message({
          type: 'success',
          message: 'Erfolgreich gelöscht',
        });
      });
    },
    logKeys(e){
      if (event.keyCode === 13) {
        this.addOrUpdateItem();
        this.barcode = '';
      }
      document.getElementById('barcode').focus();
    },
    addOrUpdateItem(){
      const item = this.items.find(item => item.barcode === this.barcode);
      const barcode = this.barcode;
      const qty = this.direction ? 1 : -1;
      if (item) {
        item.qty = item.qty + qty;
      } else {
        productResource.get(this.barcode)
          .then(product => this.addProduct(product, qty))
          .catch(() => {
            this.items.push({
              barcode: barcode,
              qty: qty,
            });
          });
      }
    },
    addProduct(product, qty){
      this.items.push({
        barcode: product.barcode,
        qty: qty,
        name: product.name,
        product_id: product.id,
        image: product.image,
      });
    },
    submitStock(){
      stocksResource.update('', this.stock)
        .then(() => {
          this.items = [];
          this.$message({
            type: 'success',
            message: 'Erfolgreich geupdatet',
          });
        }).catch(() => {
          this.$message({
            type: 'error',
            message: 'Es lief was schief.',
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
    .el-table .out {
      background: #f9ebeb;
    }

    .el-table .in {
      background: #f0f9eb;
    }

    .el-image{
      box-shadow: 0 2px 6px -2px rgba(0, 0, 0, 0.1);
      width: 50px;
      height: 50px;
      margin: 5px 1em 5px 0;
    }
    .barcode{
      font-family: 'Courier New', Courier, monospace;
      font-weight: 600;
    }
    .barcode-label{
      font-family: inherit;
      font-weight: inherit;
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
    .toolbar .el-button{
      font-size: 18px;
    }
    .toolbar{
      position: fixed;
      bottom: 0;
      right: 0;
      padding: 25px 50px 25px 25px;
      border-radius: 20px 0 0 0;
      box-shadow: 0 2px 10px -5px rgba(0,0,0,.5);
      background: #ffffff;
      z-index: 100;
    }
  }
</style>
