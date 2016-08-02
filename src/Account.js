import React, { Component } from 'react';

//アカウント
export default class Account extends Component {render() {
    return (
      <form id="account-config">
        <h2>アカウント情報</h2>
        <input type="text" id="account-name" placeholder="ユーザー名" />
        <input type="email" placeholder="メールアドレス" />
        <input type="email" placeholder="メールアドレス（確認）" />
        <input type="password" placeholder="現在のパスワード" />
        <input type="password" placeholder="新しいパスワード" />
        <input type="password" placeholder="新しいパスワード（確認）" />
        <input type="submit" className="button01" defaultValue="アカウント情報を変更" />
        <a href="/logout" className="button02">ログアウト</a>
      </form>
    );
  }
}
