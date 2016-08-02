import React, { Component } from 'react';
import SignupForm from './Signup';

//ログイン
export default class LoginForm extends Component {
  constructor(props) {
    super(props)
	this.handleLogin = this.handleLogin.bind(this);
  }	
  handleLogin(e) {
    e.preventDefault();
    $('#spinner').css('display', 'block');
    inputEmail = this.refs.email.value;
    inputPassword = this.refs.password.value;
    inputRemember = this.refs.remember.checked;
    if(inputPassword===''||inputEmail===''){
      $('#spinner').css('display', 'none');
    }
    else {
      $.ajax({
        url: './login',
        type: 'POST',
        data: {"email": inputEmail, "password": inputPassword, "remenber": inputRemember},
        timeout:10000,
        })
        .done(function(data) {
        console.log(data);
          inputEmail = '';
          inputPassword = '';
          inputRemember = '';
          location.reload();
        })
        .fail(function(xhr, status, err) {
          $('#spinner').css('display', 'none');
          alert(err);
        });
    };
  }
  render() {
    return (
      <div className="login">
        <form id="form-login">
          <p>{this.props.text}</p>
          <input type="email" placeholder="example@example.com" name="email" ref="email" required />
          <input type="password" placeholder="password" name="password" ref="password" required />
          <button type="button" className="button01" onClick={this.handleLogin}>ログイン</button>
          <div className="checkbox-list"><input type="checkbox" id={this.props.id} name="remember" ref="remember" /><label htmlFor={this.props.id}>ログイン情報を保存</label></div>
        </form>
        <SignupForm />
        <button className="button02 register-toggle">新規アカウント登録</button>
      </div>
    );
  }
}
