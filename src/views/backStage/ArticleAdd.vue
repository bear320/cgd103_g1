<template>
    <Header />
    <article class="bs-nav-space wrapper">
        <h1>公告管理</h1>
        <h2>新增文章</h2>
        <form enctype="multipart/form-data" ref="article-form">
            <label>
                <input
                    type="radio"
                    name="news_status"
                    v-model="news_status"
                    value="1"
                    checked
                    required
                />文章立即上傳更新
            </label>
            <label>
                <input
                    type="radio"
                    name="news_status"
                    v-model.number="news_status"
                    value="0"
                />文章僅先暫存為草稿
            </label>
            <div>
                <p>標題:</p>
                <input
                    type="text"
                    name="news_title"
                    v-model="news_title"
                    required
                />
            </div>
            <div class="display">
                <div>
                    <p>分類:</p>
                    <select
                        name="news_category"
                        id="artclesCategory"
                        v-model="news_category"
                    >
                        <option value="none" selected disabled hidden></option>
                        <option value="最新消息">最新消息</option>
                        <option value="園區資訊">園區資訊</option>
                        <option value="資金運用">資金運用</option>
                    </select>
                </div>
                <div>
                    <p>圖片:</p>
                    <input type="file" name="news_img" />
                </div>
            </div>
            <div>
                <p>內文:</p>
                <textarea
                    name="news_content"
                    id=""
                    cols="30"
                    rows="10"
                    v-model="news_content"
                    required
                ></textarea>
            </div>
            <button class="btn-paramy" @click.prevent="postNewArticle">
                <img src="@/assets/images/icon/confirm.svg" alt="" />確認
            </button>
        </form>
    </article>
</template>

<script>
import Header from "@/components/backStage/Header.vue";
import { BASE_URL } from "@/assets/js/common.js";
export default {
    components: {
        Header,
    },
    data() {
        return {
            news_status: "",
            news_title: "",
            news_category: "",
            news_content: "",
            news_star: "0",
        };
    },
    methods: {
        // 舊版
        /* postNewArticle() {
            let date = new Date();
            const apiURL = new URL(`${BASE_URL}/postNewArticle.php`);
            const articleUpdate = {
                news_img: this.news_img,
                news_title: this.news_title,
                news_content: this.news_content,
                news_date: date.toISOString().split("T")[0],
                news_category: this.news_category,
                news_status: this.news_status,
                news_star: 0,
            };

            fetch(apiURL, {
                method: "POST",
                body: new URLSearchParams(articleUpdate),
            })
                .then((res) => res.json())
                .then((status) => {
                    // alert(status.msg);
                    this.confirmModal();
                });
        }, */
        postNewArticle() {
            let date = new Date();
            const apiURL = new URL(`${BASE_URL}/postNewArticle.php`);

            // 取得form表單元素相當於document.querySelector(".form")
            // console.log(this.$refs["article-form"]);
            // 產生formData物件，內容包含表單裡Input的value
            const formData = new FormData(this.$refs["article-form"]);

            // formData物件新增鍵值對，相當於
            /* {
                // 表單內容
                // news_status: "...";
                // news_title: "...";
                // news_category: "...";
                // news_content: "...";
                // news_img: "...";
                // append新增的內容
                // news_date:"..."
                // news_star: "...";
            } */
            formData.append("news_date", date.toISOString().split("T")[0]);
            formData.append("news_star", this.news_star);

            fetch(apiURL, {
                method: "POST",
                // 用formData物件裡的資料作為請求體傳給php
                body: formData,
            })
                .then((res) => res.json())
                .then((status) => {
                    // console.log(status);
                    this.confirmModal();
                });
        },
        confirmModal() {
            this.$Modal.confirm({
                title: "已新增文章",
                content: "<p>已新增文章，是否關閉此分頁？</p>",
                okText: "是",
                cancelText: "否",
                onOk: () => {
                    this.$Modal.remove();
                    window.close();
                },
            });
        },
    },
};
</script>

<style lang="scss" scoped>
html article {
    text-align: left;

    h1 {
        display: inline-block;
        width: 25%;
        background-image: url(@/assets/images/backstageindex/articleIcon.png);
        @include bgSetting(contain, right);
    }
    h2 {
        color: $green;
        @include borderLeft(30px);
    }
    label {
        font-size: $h4;
        display: block;
        margin: 15px 0;
    }
    div {
        margin: 10px;
    }
    p {
        font-size: $h4;
    }
    .display {
        display: flex;
    }
    button {
        @include btnSize(30px);
        float: right;

        img {
            margin-right: 10px;
            vertical-align: middle;
        }
    }
    input[type="text"] {
        width: 40%;
        cursor: text;
    }
    textarea {
        cursor: text;
    }
    input[type="file"]::file-selector-button {
        border: 1px solid $btn-color;
        color: $btn-color;
        margin: 5px;
        padding: 5px 10px;
        border-radius: 5px;
        background-color: $bg-color;
        transition: 0.5s;
        cursor: pointer;

        &:hover {
            background-color: $btn-light-color;
        }
    }
}
</style>
