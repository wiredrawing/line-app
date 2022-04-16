@include("admin.common.header")
ここはLINEメッセージ予約の作成画面


<div id="register-reserve">
  @{{ lineReserve }}
  @{{ errors }}
  <!-- メッセージの入力項目-->

  <!-- 動的なメッセージの追加 -->

  <template v-for="(message, index) in lineReserve.messages">
    <p>
      <message-component v-bind:index="index"
                         v-bind:type="message.type"
                         v-bind:text="message.text"
                         v-bind:key="message.index"
                         @delete-message-component="deleteMessageComponent"
                         @update-message-component="updateMessageComponent"></message-component>
    </p>
  </template>

  <!-- 配信予定日 -->
  @{{ date }}
  <Datepicker v-model="date" inline autoApply></Datepicker>
  <!-- 配信予定時間の設定 -->
  <select v-model="deliveryHour">
    @{{ hourList }}
    <option v-for="(value, index) in hourList" v-bind:value="value.value">
      @{{ value.text }}
    </option>
  </select>

  <select v-model="deliveryMinute">
    @{{ hourList }}
    <option v-for="(value, index) in minuteList" v-bind:value="value.value">
      @{{ value.text }}
    </option>
  </select>

  <p v-on:click="addMessageComponent">メッセージを追加</p>

  <p @click="registerReserve">上記の内容でLINEメッセージの予約を行う</p>
</div>


<!-- 送信するメッセージのコンポーネント -->
<script id="message-component" type="text/x-template">
  @{{ index }}
  @{{ text }}
  @{{ type }}
  <p><textarea v-model="innerText" v-on:change="updateText"></textarea></p>
  <p type="button" @click="deleteThisMessage(index)">このメッセージを削除</p>
</script>

<script>

  // ----------------------------------------------
  // 親コンポーネント
  // ----------------------------------------------
  const app = Vue.createApp({
    components: { Datepicker: VueDatePicker },
    data: function () {
      return {
        lineReserve: {
          line_account_id: null,
          delivery_date: null,
          delivery_time: null,
          delivery_datetime: null,
          messages: [
            {
              index: 0,
              type: "text",
              text: null,
            }
          ],
        },
        messageId: 0,
        message: {
          index: null,
          type: "text",
          text: null,
        },
        hourList: [],
        minuteList: [],
        deliveryHour: null,
        deliveryMinute: null,
        deliveryDate:null,
        errors: {},
      }
    },
    mounted: function () {
      // 時リスト
      for (let i = 0; i < 24; i++) {
        this.hourList.push({
          value: i,
          text: i + "時"
        });
      }
      // 分リスト
      for (let i = 0; i < 60; i++) {
        this.minuteList.push({
          value: i,
          text: i + "分"
        });
      }
      console.log(this.hourList);
      console.log(this.minuteList);
    },
    methods: {
      // メッセージボックスの追加を行う
      addMessageComponent: function () {
        this.messageId = this.messageId + 1;
        this.lineReserve.messages.push({
          index: this.messageId,
          type: "text",
          text: null,
        })
      },
      // --------------------------------------------------------------------------
      // 子コンポーネントからのイベント
      // メッセージコンポーネントのアップデート
      updateMessageComponent: function (message) {
        let index = message.index;
        this.lineReserve.messages.splice(index, 1, message)
      },
      // メッセージコンポーネントの削除
      deleteMessageComponent: function (index) {
        if (confirm(index + " : このメッセージを削除します.よろしいですか?")) {
          this.lineReserve.messages.splice(index, 1);
        }
      },
      // --------------------------------------------------------------------------
      // バックエンドのAPIエンドポイントへPOSTリクエスト
      // --------------------------------------------------------------------------
      registerReserve: function () {
        let apiEndPoint = "/admin/api/line/reserve/reserve/1";
        let self = this;
        axios.post(apiEndPoint, this.lineReserve).then(function (data) {
          console.log(data);
          let response = data.data;
          if (response.status && response.data === true) {
            alert("APIリクエスト成功");
            return true
          }
          self.errors = response.errors;
          return false;
        }).catch(function (data) {
          console.log(data);
        })
      }
    }
  });


  // ----------------------------------------------
  // メッセージ用コンポーネント
  // ----------------------------------------------
  app.component("message-component", {
    data: function () {
      return {
        innerIndex: this.index,
        innerType: this.type,
        innerText: this.text,
      }
    },
    props: [
      "index",
      "type",
      "text",
    ],
    template: "#message-component",
    emits: [
      "update-message-component",
      "delete-message-component"
    ],
    mounted: function () {
      // 親コンポーネントからわたされた内容を設定
      this.innerIndex = this.index;
      this.innerType = this.type;
      this.innerText = this.text;
    },
    methods: {
      updateText: function () {
        this.$emit("update-message-component", {
          index: this.innerIndex,
          text: this.innerText,
          type: this.innerType,
        })
      },
      deleteThisMessage: function () {
        this.$emit("delete-message-component", this.index);
      }
    }
  });
  // コンポーネントの登録が完了次第 mountメソッドを実行
  app.mount('#register-reserve');
</script>
@include("admin.common.footer")
