<template>
    <Header />
    <article class="bs-nav-space wrapper">
        <h1>公告管理</h1>
        <div class="todo">
            <router-link to="bs-article-add" target="_blank"
                ><img src="@/assets/images/icon/FilePlus.svg" alt=""
            /></router-link>
            <select
                name="category"
                v-model="selectCategory"
                id="selectCategory"
                @change="choose()"
            >
                <option
                    v-for="option in options"
                    :key="option.value"
                    :value="option.value"
                    :selected="option.selected"
                >
                    {{ option.value }}
                </option>
            </select>
        </div>
        <Tabs>
            <TabPane label="已上傳">
                <section>
                    <div class="bstitle">
                        <h3 class="star"></h3>
                        <h3 class="article">公告</h3>
                        <h3 class="pic">圖片</h3>
                        <h3 class="date">日期</h3>
                        <h3 class="tag">分類</h3>
                        <h3 class="operator">操作</h3>
                    </div>
                    <div
                        class="content"
                        v-for="(article, index) in selectCategory == '所有公告'
                            ? uploaded
                            : newarticles"
                        :key="index"
                    >
                        <label
                            :class="
                                article.news_star == 1 ? 'starmark' : 'star'
                            "
                        >
                            <svg
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                x="0px"
                                y="0px"
                                viewBox="0 0 47.94 47.94"
                                style="enable-background: new 0 0 47.94 47.94"
                                xml:space="preserve"
                            >
                                <path
                                    style="fill: currentColor,stoke: currentColor;"
                                    d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757
	c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042
	c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685
	c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528
	c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956
	C22.602,0.567,25.338,0.567,26.285,2.486z"
                                />
                            </svg>
                            <input
                                type="checkbox"
                                style="display: none"
                                :true-value="1"
                                :false-value="0"
                                @change="marked(index)"
                                v-model.number="article.news_star"
                            />
                        </label>

                        <div class="article">
                            <p>{{ article.news_title }}</p>
                            <p class="article-content">
                                {{ article.news_content }}
                            </p>
                        </div>
                        <div class="pic">
                            <img
                                :src="`./images/article/${article.news_img}`"
                                alt=""
                            />
                        </div>
                        <p class="date">{{ article.news_date }}</p>
                        <p class="tag">{{ article.news_category }}</p>
                        <div class="operator">
                            <router-link
                                :to="`/bs-article-edit/${article.news_id}`"
                                target="_blank"
                                ><img src="@/assets/images/icon/edit.svg"
                            /></router-link>
                        </div>
                    </div>
                </section>
                <div class="pagination">
                    <ul>
                        <a @click="prePage"><li>&lt;</li></a>
                        <a
                            v-for="i in totalPage"
                            :class="{ 'is-active': currentPage == i }"
                            :key="i"
                            href="#"
                            @click="changePage(i)"
                            ><li>{{ i }}</li></a
                        >

                        <a @click="nextPage"><li>></li></a>
                    </ul>
                </div>
            </TabPane>
            <TabPane label="草稿">
                <section>
                    <div class="bstitle">
                        <h3 class="star"></h3>
                        <h3 class="article">公告</h3>
                        <h3 class="pic">圖片</h3>
                        <h3 class="date">日期</h3>
                        <h3 class="tag">分類</h3>
                        <h3 class="operator">操作</h3>
                    </div>
                    <div
                        class="content"
                        v-for="(article, index) in selectCategory == '所有公告'
                            ? draft
                            : newsdraft"
                        :key="index"
                    >
                        <label
                            :class="
                                article.news_star == 1 ? 'starmark' : 'star'
                            "
                        >
                            <svg
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                x="0px"
                                y="0px"
                                viewBox="0 0 47.94 47.94"
                                style="enable-background: new 0 0 47.94 47.94"
                                xml:space="preserve"
                            >
                                <path
                                    style="fill: currentColor,stoke: currentColor;"
                                    d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757
                            c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042
                            c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685
                            c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528
                            c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956
                            C22.602,0.567,25.338,0.567,26.285,2.486z"
                                />
                            </svg>
                            <input
                                type="checkbox"
                                style="display: none"
                                :true-value="1"
                                :false-value="0"
                                @change="marked(index)"
                                v-model.number="article.news_star"
                            />
                        </label>
                        <div class="article">
                            <p>{{ article.news_title }}</p>
                            <p class="article-content">
                                {{ article.news_content }}
                            </p>
                        </div>
                        <div class="pic">
                            <img
                                :src="`./images/article/${article.news_img}`"
                                alt=""
                            />
                        </div>
                        <p class="date">{{ article.news_date }}</p>
                        <p class="tag">{{ article.news_category }}</p>
                        <div class="operator">
                            <router-link
                                :to="`/bs-article-edit/${article.news_id}`"
                                target="_blank"
                                ><img src="@/assets/images/icon/edit.svg"
                            /></router-link>
                            <span style="cursor: pointer" @click="modal = true">
                                <img
                                    src="@/assets/images/icon/delete.svg"
                                    alt=""
                            /></span>
                            <Modal
                                v-model="modal"
                                title="刪除公告?"
                                ok-text="確認"
                                cancel-text="取消"
                                @on-ok="deleteArticle(index)"
                                @on-cancel="cancel"
                            >
                                <p>公告刪除後將無法回復動作，確定要刪除嗎?</p>
                            </Modal>
                        </div>
                    </div>
                </section>
                <div class="pagination">
                    <ul>
                        <a @click="prePage"><li>&lt;</li></a>
                        <a
                            v-for="i in totalPage"
                            :class="{ 'is-active': currentPage == i }"
                            :key="i"
                            href="#"
                            @click="changePage(i)"
                            ><li>{{ i }}</li></a
                        >

                        <a @click="nextPage"><li>></li></a>
                    </ul>
                </div>
            </TabPane>
        </Tabs>
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
            currentPage: this.$route.query.page ? this.$route.query.page : 1,
            articles: [],
            uploaded: [],
            draft: [],
            modal: false,
            options: [
                { value: "所有公告", selected: true },
                { value: "最新消息" },
                { value: "園區資訊" },
                { value: "資金運用" },
            ],
            selectCategory: "所有公告",
            newarticles: [],
            newsdraft: [],
        };
    },
    computed: {
        totalPage() {
            return Math.ceil(this.articles.length / 10);
        },
        filterList() {
            return this.articles.slice(
                (this.currentPage - 1) * 10,
                this.currentPage * 10
            );
        },
    },
    methods: {
        choose() {
            let tag = document.getElementById("selectCategory").value;
            const apiURL = new URL(`${BASE_URL}/getArticleList.php`);
            fetch(apiURL)
                .then((res) => res.json())
                .then((json) => {
                    this.articles = json;
                    this.newarticles = this.articles.filter((article) => {
                        return (
                            article.news_status == "1" &&
                            article.news_category == tag
                        );
                    });
                    this.newsdraft = this.articles.filter((article) => {
                        return (
                            article.news_status == "0" &&
                            article.news_category == tag
                        );
                    });
                });
        },
        getArticleList() {
            const apiURL = new URL(`${BASE_URL}/getArticleList.php`);
            fetch(apiURL)
                .then((res) => res.json())
                .then((json) => {
                    this.articles = json;
                    this.uploaded = this.articles.filter((article) => {
                        return article.news_status == "1";
                    });
                    this.draft = this.articles.filter((article) => {
                        return article.news_status == "0";
                    });
                });
        },
        marked(index) {
            const apiURL = new URL(`${BASE_URL}/editArticle.php`);

            const news_id = this.articles[index].news_id;
            const news_star = this.articles[index].news_star;

            fetch(apiURL, {
                method: "POST",
                body: new URLSearchParams({
                    type: "star",
                    news_id: this.articles[index].news_id,
                    news_star: this.articles[index].news_star,
                }),
            })
                .then((res) => res.json())
                .then((json) => {
                    this.resDate = json;
                });
        },
        confirmModal() {
            this.$Modal.confirm({
                title: "確定要刪除公告?",
                content: "<p>公告刪除後將無法回復動作，確定要刪除嗎?</p>",
                okText: "確定",
                cancelText: "取消",
                onOk: () => {
                    this.deleteArticle(index);
                    this.$Modal.remove();
                },
            });
        },
        deleteArticle(index) {
            const apiURL = new URL(`${BASE_URL}/deleteArticle.php`);
            fetch(apiURL, {
                method: "POST",
                body: new URLSearchParams({
                    news_id: this.articles[index].news_id,
                }),
            })
                .then((res) => res.json())
                .then((result) => {
                    location.reload();
                    this.$Message.info("已刪除公告");
                });
        },
        cancel() {
            this.$Message.info("已取消");
        },
        prePage() {
            if (this.currentPage == 1) {
                return;
            } else {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage === this.totalPage) {
                return;
            } else {
                this.currentPage++;
            }
        },
        changePage(page) {
            this.currentPage = page;
        },
    },
    created() {
        this.getArticleList();
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
    .todo {
        float: right;
        display: flex;
        img {
            margin-right: 15px;
        }
    }

    section {
        %star {
            width: 5%;
            cursor: pointer;
            user-select: none;
        }

        %starsvg {
            width: 25px;
            margin-left: 10px;
        }
        .star {
            @extend %star;
            svg {
                @extend %starsvg;
                fill: rgb(212, 211, 211);
            }
        }
        .starmark {
            @extend %star;
            svg {
                @extend %starsvg;
                fill: #ed8a19;
            }
        }
        .article {
            width: 45%;
            margin-right: 20px;

            p:first-child {
                font-weight: 700;
                font-size: $h4;
            }

            .article-content {
                display: -webkit-inline-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
                -webkit-line-clamp: 3;
            }
        }
        .pic,
        .date {
            width: 15%;

            img {
                width: 120px;
                height: 120px;
            }
        }

        .tag,
        .operator {
            width: 10%;
            display: flex;

            img {
                margin: 10px;

                &:hover {
                    stroke: $green;
                }
            }
        }
        .bstitle {
            display: flex;
            width: 100%;
            background-color: $darkgreen;
            color: white;
            border-radius: 10px;
        }

        .content {
            display: flex;
            align-items: center;
            padding: 10px 0;
            margin-bottom: 10px;
            border-bottom: solid 1px $lightgreen;
        }
    }
    .pagination {
        padding: 30px 0;
        text-align: center;
        a {
            display: inline-block;
            padding: 10px 18px;
            color: $darkgreen;
            //
            width: 40px;
            height: 40px;
            line-height: 40px;
            padding: 0;
            text-align: center;
            &:hover {
                color: $darkgreen;
            }
            &:focus {
                color: #fff;
            }
        }
        a.is-active {
            background-color: $darkgreen;
            border-radius: 100%;
            color: #fff;
        }
    }
}
</style>
