<template>
  <div class="image-upload-container">
    <el-upload
      ref="upload"
      :action="action"
      list-type="picture"
      :on-progress="onProgress"
      :on-success="onSuccess"
      :show-file-list="false"
      :multiple="false"
      drag
    >
      <transition name="el-fade-in">
        <el-progress v-show="uploading" type="circle" :status="status" :percentage="percentage" :width="50" />
      </transition>
      <el-image
        :src="image"
        fit="cover"
        :class="uploading ? 'blur-image' : ''"
      >
        <div slot="error" class="image-slot">
          <i class="el-icon-picture-outline" />
        </div>
      </el-image>
    </el-upload>
  </div>
</template>

<script>
export default {
  name: 'ImageUpload',
  props: {
    image: {
      type: String,
      default: '',
    },
    action: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      percentage: 0,
      uploading: false,
      status: null,
    };
  },
  methods: {
    onProgress(event, file, fileList){
      this.percentage = Math.round(event.percent);
      this.uploading = true;
    },
    onSuccess(response, file, fileList){
      this.image = file.response.image;
      this.status = 'success';
      this.$refs.upload.clearFiles();
      setTimeout(() => {
        this.uploading = false;
        this.status = null;
      }, 500);
    },
  },
};
</script>

<style lang="scss" scoped>
@import "~@/styles/mixin.scss";
.image-upload-container /deep/ {
  .el-progress{
    display: flex;
    position: absolute;
    background: rgba(255,255,255,0.6);
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    z-index: 200;
  }
  .el-progress__text{
    font-size: 11px!important;
    font-weight: 800;
  }
  .el-progress__text .el-icon-check{
    font-size: 21px!important;
  }
  .blur-image .el-image__inner{
    -webkit-filter: blur(5px); /* Safari 6.0 - 9.0 */
    filter: blur(5px);
  }
  .el-upload--picture{
      box-shadow: 0 2px 6px -2px rgba(0, 0, 0, 0.1);
      width: 64px;
      height: 64px;
      margin: 5px 1em 5px 0;
      border: 1px solid transparent;
      border-radius: 6px;
      overflow: hidden;
    }
    .el-upload--picture:hover {
      border-color: #0067ce;
      color: #0067ce;
    }

    .el-upload-dragger,
    .el-image{
      width: 100%;
      height: 100%;
      display: block;
    }
    .el-image img{
      width: 101%;
      height: 101%;
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
}
</style>
