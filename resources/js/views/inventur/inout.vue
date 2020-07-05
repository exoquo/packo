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
      />
      <el-table-column
        label="Produkt"
      >
        <template slot-scope="scope">
          <div class="product-column">
            <el-image
              :src="scope.row.img"
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
      <el-input ref="barcode" v-model="barcode" placeholder="Barcode" size="medium" style="width:10em;margin-right:1em" />
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
      <el-button icon="el-icon-check" type="success" style="margin-left:2em">Abschließen</el-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Inventur',
  components: {},
  data() {
    return {
      barcode: '',
      direction: false,
      items: [{
        barcode: '2016-05-03',
        qty: -2,
        name: 'INOXSIGN N.30.E',
      }, {
        barcode: '2016-05-02',
        qty: 5,
        name: 'INOXSIGN N.12.E',
      }, {
        barcode: '2016-05-04',
        qty: 1,
        name: 'INOXSIGN N.10.E',
      }, {
        barcode: '2016-05-01',
        qty: -5,
        name: 'INOXSIGN N.03.E',
      }],
    };
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
        this.addItemToList();
        this.barcode = '';
      }
      this.$refs.barcode.$el.children[0].focus();
    },
    addItemToList(){
      const item = this.items.find(item => item.barcode === this.barcode);

      if (item) {
        const qty = this.direction ? 1 : -1;
        item.qty = item.qty + qty;
      } else {
        this.items.push({
          barcode: this.barcode,
          qty: this.direction ? 1 : -1,
          name: 'toDo',
        });
      }
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss">
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
</style>

<style rel="stylesheet/scss" lang="scss" scoped>
  .container {
    min-height: 100vh;
    padding-bottom: 100px;
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

</style>
