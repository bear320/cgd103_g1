<template>
    <div class="lightbox">
        <div class="modal-mask" :style="modalStyle">
            <div class="modal-container" @click.self="toggleModal">
                <div class="modal-body">
                    <div class="img">
                        <slot name="img"></slot>
                    </div>
                    <div class="article">
                        <slot name="article-title"></slot>
                        <div class="tag">
                            <slot name="article-tag"></slot>
                            <slot name="article-date"></slot>
                        </div>
                        <div class="content">
                            <slot name="article-content"></slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
button {
    font-size: 1rem;
}

.lightbox {
    position: absolute;
    display: block;
}

.modal-mask {
    position: fixed;
    z-index: 990;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    display: table;
    background-color: rgba(56, 57, 56, 0.5);
}

.modal-container {
    cursor: pointer;
    display: table-cell;
    vertical-align: middle;
}

.modal-body {
    cursor: auto;
    display: flex;
    width: 75vw;
    margin: 0 auto;
    padding: 2rem;
    background-color: #fff;
    border-radius: 10px;

    @include d {
        flex-direction: column;
    }
    @include m {
        padding: 20px;
        width: 90%;
    }

    .img {
        width: 40%;
        height: 60vh;
        overflow: hidden;

        // &::slotted(img) {
        //     width: 100%;
        //     height: 100%;
        //     object-fit: cover;
        // } 不知為啥沒用
        @include d {
            width: 65vw;
            height: 35vh;
            margin: auto;
        }
        @include m {
            width: 60vw;
            height: 20vh;
        }
    }
    .article {
        padding: 3rem;
        width: 60%;

        @include d {
            width: 100%;
        }
        @include m {
            padding: 20px;
        }

        .tag {
            display: flex;
            color: $lightgreen;
            margin-bottom: 20px;
            width: 35%;
            justify-content: space-between;
            @include m {
                width: 50%;
            }
        }
        .content {
            @include m {
                height: 30vh;
                overflow-y: scroll;
                padding-right: 15px;
            }
        }
    }
}
</style>
<script>
export default {
    data() {
        return {
            isShow: false,
        };
    },
    computed: {
        modalStyle() {
            return {
                display: this.isShow ? "" : "none",
            };
        },
    },
    methods: {
        toggleModal() {
            this.isShow = !this.isShow;
        },
    },
};
</script>
