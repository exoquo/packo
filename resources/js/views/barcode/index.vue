<template>
  <div>
    <el-container style="text-align:center">
      <el-row>
        <el-col v-for="n in 40" :key="n" :span="6" class="barcode-box">
          <el-barcode :flat="true" :width="2" :height="60" :value="barcode" :format="barcode.length == 13 ? 'EAN13' : 'CODE128'" />
          {{ text }}
        </el-col>
      </el-row>
    </el-container>
    <el-card class="toolbar">
      <el-select v-model="selected" filterable placeholder="Select">
        <el-option
          v-for="product in products.filter(procuct => procuct.barcode !== '')"
          :key="product.id"
          :label="product.label_text"
          :value="product.id"
        />
      </el-select>
      <el-button style="margin-left:1em;font-size:1.2em" type="success" icon="el-icon-printer" circle @click.prevent="print" /></el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import VueBarcode from 'vue-barcode';

const productResource = new Resource('products');

export default {
  name: 'Barcode',
  components: {
    'el-barcode': VueBarcode,
  },
  data() {
    return {
      barcode: 'SAACSDDA3',
      text: 'Barcode',
      products: [],
      selected: null,
    };
  },
  watch: {
    selected: function(id) {
      var product = this.products[id - 1];
      this.barcode = product.barcode;
      this.text = product.label_text;
    },
  },
  created(){
    this.fetchProducts();
  },
  methods: {
    async fetchProducts(){
      const data = await productResource.list();
      this.products = data;
      this.selected = 1;
    },
    print(){
      window.print();
      console.log('print');
    },
  },

};
</script>
<style>
@media print {
  .sidebar-container,
    .fixed-header,
    .toolbar{
      display: none;
    }
    #app .main-container,
    .app-main{
      width: 210mm!important;
      height: 296.9mm!important;
      overflow: hidden!important;
      margin: 0!important;
      padding: 0!important;
    }
}
    .el-container{
      width: 210mm!important;
      height: 296.9mm!important;
      padding: 0!important;
      overflow: hidden!important;
      margin:0 auto!important;
    }
    .el-col{
      padding: 3mm;
      height: 29.7mm;
      overflow: hidden!important;
    }
    .vue-barcode-element{
      width:100%!important;
      margin-top: -6mm;
      margin-bottom: -4mm;
    }
</style>
<style lang="scss" scoped>
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
    .toolbar{
      position: fixed;
      bottom: 10px;
      right: 10px;
      z-index: 100;
    }
</style>
