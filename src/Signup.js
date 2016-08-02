import React, { Component } from 'react';//アカウント登録
export default class SignupForm extends Component {render() {
    return (
      <form id="form-signup" action="/register" method="post">
        <h2>新規アカウント登録</h2>
        <input type="text" placeholder="アカウント名" name="name" />
        <input type="email" placeholder="メールアドレス" name="email" />
        <input type="password" placeholder="パスワード" name="password" />
        <input type="password" placeholder="パスワード（確認用）" name="password_confirmation" />
        <input type="submit" className="button01" value="新規アカウント登録" />
      </form>
    );
  }
}
