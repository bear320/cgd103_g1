<template lang="">
    <Header></Header>
    <main class="nav-space">
        <div class="wrapper">
            <div class="breadcrumb">
                <router-link to="/home">Home</router-link>
                <router-link to="/shop">周邊商城</router-link>
                <router-link
                    :to="`/shop?category=${source.prod_category}&order=prod_name&limit=9&page=1`"
                >
                    {{ breadCategory }}
                </router-link>
                <router-link :to="`/support/`">
                    {{ source.prod_name }}
                </router-link>
            </div>
            <Row>
                <Col span="24" :xl="12" :md="24">
                    <div class="img-slider">
                        <image-slider :imgs="images"></image-slider>
                    </div>
                </Col>
                <Col span="24" :xl="12" :md="24">
                    <div class="text">
                        <h1 class="product-name">{{ source.prod_name }}</h1>
                        <p class="product-description">
                            {{ source.prod_info }}
                        </p>
                        <div class="product-additional">
                            <p class="additional-info">
                                購買此商品可獲得&nbsp{{ coinTotal }}
                            </p>
                            <img
                                class="additional-img"
                                src="../assets/images/product/coin.png"
                                alt=""
                            />
                        </div>
                        <div class="product-price">NTD$&nbsp{{ sumTotal }}</div>
                        <div class="product-box">
                            <div class="quantity-box">
                                <div class="quantity-text">數量</div>
                                <div class="quantity">
                                    <Icon
                                        class="md-remove"
                                        type="md-remove"
                                        @click="changeQuantity('-')"
                                    />
                                    <input type="number" v-model="quantity" />
                                    <Icon
                                        class="md-add"
                                        type="md-add"
                                        @click="changeQuantity('+')"
                                    />
                                </div>
                            </div>
                            <div class="share-box">
                                <div class="share-text">Share</div>
                                <div class="community-group">
                                    <img
                                        class="community-item"
                                        src="../assets/images/product/instagram.png"
                                        alt=""
                                    />
                                    <img
                                        class="community-item"
                                        src="../assets/images/product/facebook.png"
                                        alt=""
                                    />
                                    <img
                                        class="community-item"
                                        src="../assets/images/product/youtube.png"
                                        alt=""
                                    />
                                    <img
                                        class="community-item"
                                        src="../assets/images/product/twitter.png"
                                        alt=""
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="btn-box">
                            <div class="btn-paramy" @click="addToCart">
                                加到購物車
                            </div>
                        </div>
                    </div>
                </Col>
            </Row>

            <Row>
                <Col span="24">
                    <div class="rel-text">相關商品</div>
                </Col>
            </Row>
            <div class="row">
                <product-card
                    v-for="(item, index) in relProducts"
                    :key="item.prod_id"
                    :proImg="item.prod_img1"
                    :proName="item.prod_name"
                    :proPrice="item.prod_price"
                    :proId="item.prod_id"
                    :path="`../images/shop/`"
                    :col="'col-xl-3 col-lg-3 col-md-6 col-6'"
                ></product-card>
            </div>
        </div>
    </main>
    <Footer></Footer>
</template>

<script>
import Header from "@/components/header.vue";
import ImageSlider from "@/components/shop/ImageSlider.vue";
import ProductCard from "@/components/shop/ProductCard.vue";
import Footer from "@/components/footer.vue";
import { BASE_URL } from "@/assets/js/common.js";

export default {
    components: {
        Header,
        ImageSlider,
        ProductCard,
        Footer,
    },
    data() {
        return {
            images: [],
            source: [],
            relProducts: [],
            quantity: 1,
        };
    },
    computed: {
        breadCategory() {
            switch (this.source.prod_category) {
                case "daily":
                    return "生活小物";
                    break;
                case "apparel":
                    return "服飾";
                    break;
                case "doll":
                    return "玩具/絨毛娃娃";
                    break;
                default:
                    break;
            }
        },
        relProd() {
            return this.relProducts.filter((prod) => {
                return prod.id !== this.source.id;
            });
        },
        sumTotal() {
            return this.source.prod_price * this.quantity;
        },
        coinTotal() {
            return this.sumTotal * 0.1;
        },
    },
    watch: {
        $route: function (q) {
            this.getProductDetail();
            this.quantity = 1;
        },
    },
    methods: {
        getProductDetail() {
            const productId = this.$route.params.id;
            const apiURL = new URL(
                `${BASE_URL}/getProductDetail.php?proId=${productId}`
            );
            fetch(apiURL)
                .then((res) => res.json())
                .then((json) => {
                    this.source = json.prod_detail;
                    this.images = json.prod_detail.images.filter((item) => {
                        return item !== "";
                    });
                    this.relProducts = json.relProd;
                });
        },
        changeQuantity(operator) {
            if (operator === "-") {
                this.quantity > 1 ? (this.quantity -= 1) : this.quantity;
            } else {
                this.quantity += 1;
            }
        },
        async addToCart() {
            let prodIndex = this.$store.state.cart.findIndex(
                (item) => item.prod_id === +this.$route.params.id
            );
            if (prodIndex < 0) {
                await this.$store.dispatch("addToCart", {
                    memId: this.$store.state.user.mem_id,
                    prodId: this.$route.params.id,
                    cartQty: this.quantity,
                });
                this.open(true, "已新增商品至購物車");
            } else {
                await this.$store.dispatch("addToCart", {
                    memId: this.$store.state.user.mem_id,
                    prodId: this.$route.params.id,
                    cartQty: (this.$store.state.cart[prodIndex].cart_qty +=
                        this.quantity),
                });
                this.open(true, "已更新商品數量");
            }
        },
        open(nodesc, title) {
            this.$Notice.open({
                title: `✔　${title}`,
            });
        },
    },

    created() {
        this.getProductDetail();
    },
    mounted() {
        window.scrollTo({
            top: 0,
            behavior: "instant",
        });
    },
};
</script>

<style lang="scss" scoped>
.wrapper {
    /* .breadcrumbs {
        display: flex;
        margin: 100px 0 80px 0;
        li {
            a {
                color: $font_color;
            }
            &::after {
                content: ">";
            }
            &:last-child::after {
                content: "";
            }
            &:last-child a {
                color: $green;
            }
        }
    } */
    .breadcrumb {
        max-width: 1000px;
        margin: 15px auto 15px 0;
        text-align: left;
        font-family: font-EL;
        a {
            color: lighten(#515a6e, 20%);
            padding-right: 20px;
        }
        a:last-child {
            color: $btn-color;
            font-family: font-R;
        }
        a + a::before {
            content: "›";
            color: lighten(#515a6e, 20%);
            margin-right: 20px;
        }
    }

    .img-slider {
        margin-bottom: 30px;
    }

    .text {
        padding: 0 3%;
        .product-name {
            margin: 15px 0;
            text-align: left;
            @include m() {
                text-align: left;
            }
        }
        .product-description {
            text-align: left;
            // padding: 0px 60px;
            line-height: 1.5;
            margin-bottom: 40px;
        }
        .product-additional {
            display: flex;
            align-items: center;
            // padding: 10px 60px;
            gap: 10px;
            margin-bottom: 30px;
            .additional-info {
                font-weight: 700;
                font-size: $h4;
            }
            .additional-img {
                width: 30px;
            }
        }
        .product-price {
            // padding: 0px 60px;
            text-align: left;
            font-weight: 700;
            font-size: $h3;
            margin-bottom: 30px;
        }
        .product-box {
            // padding: 0px 60px;
            display: flex;
            gap: 20px;
            margin-bottom: 50px;
            .quantity-text {
                text-align: left;
                margin-bottom: 10px;
            }
            .quantity {
                position: relative;
                input {
                    text-align: center;
                    padding: 0 25px;
                    width: 160px;
                }
                input[type="number"]::-webkit-outer-spin-button,
                input[type="number"]::-webkit-inner-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                }
                .md-remove {
                    position: absolute;
                    top: 50%;
                    left: 0%;
                    transform: translate(0%, -50%);
                    color: $font_color;
                    border-right: 1px solid black;
                    padding: 5px;
                }
                .md-add {
                    position: absolute;
                    top: 50%;
                    right: 0%;
                    transform: translate(0%, -50%);
                    color: $font_color;
                    border-left: 1px solid black;
                    padding: 5px;
                }
            }
            .share-text {
                text-align: left;
                margin-bottom: 10px;
            }
            .community-group {
                img {
                    width: 20px;
                    margin-right: 10px;
                }
            }
        }
        .btn-box {
            // padding: 0px 60px;
            .btn-paramy {
                font-size: $h3;
                width: 180px;
            }
        }
    }
    .rel-text {
        margin: 110px 0;
        font-size: $h3;
    }
}
</style>
