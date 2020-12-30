<template>
  <div>
    <el-container style="text-align:center">
      <el-row>
        <el-col v-for="n in 40" :key="n" :span="6" class="barcode-box">
          <el-barcode :class="barcode.length == 13 ? 'EAN13' : 'CODE128'" :margin="0" :font-size="0" :flat="true" :width="2" :height="42" :value="barcode" :format="barcode.length == 13 ? 'EAN13' : 'CODE128'" />
          <div class="data">
            <el-image :src="image" />
            <div class="discription">
              <div class="text">{{ text }}</div>
              <div class="material">{{ material }}</div>
            </div>
          </div>
        </el-col>
      </el-row>
    </el-container>
    <el-card class="toolbar">
      <el-select v-model="selected" filterable placeholder="Select">
        <el-option
          v-for="(product, index) in products.filter(procuct => procuct.barcode !== '')"
          :key="index"
          :label="product.label_text"
          :value="index"
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
      material: 'Messing',
      image: 'https://image.inoxsign.com/no.pic',
      products: [],
      selected: null,
      design: '',
    };
  },
  watch: {
    selected: function(id) {
      var product = this.products[id - 1];
      this.barcode = product.barcode;
      this.text = product.label_text.replace('INOXSIGN ', '');
      const design = product.label_text.replace('INOXSIGN ', '').split('.');

      const material = design.pop();
      if (material === 'M'){
        this.material = 'Messing';
      }
      if (material === 'E'){
        this.material = 'Edelstahl';
      }
      if (material === 'R'){
        this.material = 'Vintage';
      }

      this.design = design.join('.');
      this.image = 'https://image.inoxsign.com/' + this.design;
    },
  },
  created(){
    this.fetchProducts();
  },
  methods: {
    async fetchProducts(){
      const data = await productResource.list();
      this.products = data;
      this.selected = this.$route.params.id | 1;
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
      position: relative;
      height: 29.7mm;
      padding: 4mm;
      overflow: hidden!important;
    }
    .vue-barcode-element{
      width:100%!important;
      height: 12mm!important;
    margin-top: -2mm;
      margin-bottom: 0;
    }
    .EAN13 .vue-barcode-element{
      margin-left: -22px;
    }

    .data{
      position: relative;
      width:100%!important;
      height: 10mm!important;
      display: flex;
      font-size: 1em;
      text-align: right;
    }
    .el-image{
      height: 19;
      width: 56mm!important;
      text-align: left;
    }
    .el-image .el-image__inner{
      width: auto;
    }
    .discription{
      padding: 0 1mm;
      width: 100%;
    }
    .discription > div{
      height: 5mm;
      width: 100%;
      overflow: hidden;
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
