<div class="el-filemanager__body--file" x-data="{ files: @entangle('files') }">
    <div class="file-list">
        <template x-for="(fileInfo, index) in files">
            <div class="file-info" :class="{ 'active': fileInfo !== undefined && fileSelect === fileInfo }"
                x-on:click="fileSelect = fileInfo">
                <div class="file-icon">
                    <i class="bi bi-file-text "></i>
                </div>
                <div class="file-name">
                    <span x-text="fileInfo.basename"></span>
                </div>
            </div>
        </template>
    </div>
    <div x-show="fileSelect" class="file-property">
        <div class="property-item" x-show="fileSelect?.basename?.match(/\.(jpg|jpeg|png|gif)$/i)">
            <p class="property-key">Thumbnail</p>
            <p class="property-value" x-text="">
                <img class="w-100" :src="fileSelect.url" />
            </p>
        </div>
        <div class="property-item">
            <p class="property-key">File Name</p>
            <p class="property-value" x-text="fileSelect.basename"></p>
        </div>
        <div class="property-item" x-show="false">
            <p class="property-key">File Path</p>
            <p class="property-value" x-text="fileSelect.realpath"></p>
        </div>
        <div class="property-item">
            <p class="property-key">File Url</p>
            <p class="property-value"><a :href="fileSelect.url" x-text="fileSelect.url" target="_blank"></a></p>
        </div>
        <div class="property-item">
            <p class="property-key">File Size</p>
            <p class="property-value" x-text="fileSelect.size_string"></p>
        </div>
        <div class="property-item">
            <p class="property-key">File Extension</p>
            <p class="property-value" x-text="fileSelect.extension"></p>
        </div>
        <div class="property-item">
            <p class="property-key">File Permission</p>
            <p class="property-value" x-text="fileSelect.permission"></p>
        </div>
        <div class="property-item">
            <p class="property-key">Create At</p>
            <p class="property-value"
                x-text='(new Date(fileSelect.atime*1000))'></p>
        </div>
        <div class="property-item">
            <p class="property-key">Update At</p>
            <p class="property-value"
                x-text='(new Date(fileSelect.mtime*1000))'></p>
        </div>
    </div>
</div>
