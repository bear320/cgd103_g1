<template>
    <!-- <Header /> -->
    <section>
        <div class="content_active">
            <div class="content_active_sign_up">
                <h1>新增管理員</h1>
                <form
                    @submit.prevent="sign_up"
                    id="sign_up_form"
                    method="post"
                    enctype="multipart/form-data"
                >
                    <div>
                        <p>帳號</p>
                        <input
                            id="emp_account"
                            type="text"
                            placeholder="管理員帳號"
                            v-model="sign_up_account"
                            required
                        />
                    </div>
                    <div>
                        <p class="content_active_sign_up_moveTxt_1">密碼</p>
                        <i class="icon_password"></i>
                        <input
                            type="text"
                            v-if="pwdType_one"
                            v-model="sign_up_password"
                        />
                        <input
                            type="password"
                            placeholder="管理員密碼"
                            v-model="sign_up_password"
                            required
                            v-else
                        />
                        <img
                            :src="seen_one ? seenImg : unseenImg"
                            @click="changeType_1"
                            v-on:mouseover="hoverEye_1"
                            v-on:mouseout="outEye_1"
                            class="sign_up_icon_eye"
                        />
                    </div>
                    <div>
                        <p>姓名</p>
                        <input
                            id="emp_account"
                            type="text"
                            placeholder="管理員姓名"
                            v-model="sign_up_name"
                            required
                        />
                    </div>
                    <div>
                        <button
                            class="btn_sign_up"
                            type="submit"
                            @click="sign_up_1"
                        >
                            加入管理員
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
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
            login_account: "",
            login_password: "",
            sign_up_account: "",
            sign_up_password: "",
            sign_up_name: "",
            seen_one: "",
            seen_two: "",
            seen_three: "",
            unseenImg: require("../../assets/images/login/eye_close.png"),
            seenImg: require("../../assets/images/login/eye_open.png"),
            pwdType_one: false,
            pwdType_two: false,
            pwdType_three: false,
        };
    },
    methods: {
        // ======================================== 看得到密碼 click事件 ======================================== //
        changeType_1: function () {
            this.seen_one = !this.seen_one;
            this.pwdType_one = !this.pwdType_one;
        },

        hoverEye_1: function () {
            this.seen_one = !this.seen_one;
        },

        outEye_1: function () {
            this.seen_one = !this.seen_one;
        },

        changeType_2: function () {
            this.seen_two = !this.seen_two;
            this.pwdType_two = !this.pwdType_two;
        },

        hoverEye_2: function () {
            this.seen_two = !this.seen_two;
        },

        outEye_2: function () {
            this.seen_two = !this.seen_two;
        },

        changeType_3: function () {
            this.seen_three = !this.seen_three;
            this.pwdType_three = !this.pwdType_three;
        },

        hoverEye_3: function () {
            this.seen_three = !this.seen_three;
        },

        outEye_3: function () {
            this.seen_three = !this.seen_three;
        },

        // ======================================== 註冊會員加進資料庫 ======================================== //
        sign_up_1() {
            let url = `${BASE_URL}/getAdminAdd.php`;
            const signUpContent = {
                account: this.sign_up_account,
                password: this.sign_up_password,
                name: this.sign_up_name,
            };

            fetch(url, {
                method: "POST",
                body: new URLSearchParams(signUpContent),
            })
            .then((res) => res.json())
            .then(() => {
                this.open();
            })
            .then(() => {
                this.$router.push({ path: "/bs-admin-list" });
            })
        },

        // ======================================== 右上角彈窗 ======================================== //
        open(open) {
            this.$Notice.open({
                title: "管理員加入成功",
                desc: open
                    ? ""
                    : `將自動跳轉至全里管理頁面`,
            });
        },
    },
};
</script>

<style lang="scss" scoped>
.content_active {
    position: relative;
    // width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.content_active_sign_up > form > div > input {
    text-align: left;
    padding: 5px ;
    margin: 3%;
    width: 0px;
    flex-grow: 8;
    color: #757575;
    border-radius:5px ;
}

.content_active_sign_up > h2 {
    font-size: 24px;
    margin-top: 12%;
    font-weight: 400;
    color: #757575;
}

.content_active_sign_up > form > div > p {
    display: inline-block;
    width: 0;
    flex-grow: 2;
}

.content_active_sign_up > form > div {
    display: flex;
    justify-content: center;
    margin-top: 7%;
    align-items: center;
    position: relative;
}

.btn_sign_up {
    background-color: #579194;
    border: none;
    padding: 10px;
    width: 200px;
    border-radius: 3px;
    box-shadow: 1px 5px 20px -5px rgba(0, 0, 0, 0.4);
    color: #eee;
    margin-top: 7.5%;
    cursor: pointer;
}

// ======================================== 眼睛 ======================================== //
.sign_up_icon_eye {
    width: 18px;
    height: 18px;
    top: 0;
    bottom: 0;
    margin: auto;
    right: -10%;

    position:absolute;
}

.login_icon_eye {
    width: 18px;
    height: 18px;
    top: 0;
    bottom: 0;
    margin: auto;
    right: -8%;
    position:absolute;
}
</style>
