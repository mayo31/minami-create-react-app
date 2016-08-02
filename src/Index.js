import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import SiteHeader from './SiteHeader';
import ArticleArea　from './ArticleArea';
import SiteFooter from './SiteFooter';

//メインコンポーネント
class Main extends Component {render() {
    return (
      <main>
        <ArticleArea />
      </main>
    );
  }
}

//bodyコンポーネント
class Body extends Component　{render() {
    return (
      <div id="container">
        <SiteHeader />
        <Main />
        <SiteFooter />
      </div>
    );
  }
}

/* React + JSX */
ReactDOM.render(
  <Body />,
  document.getElementById('body')
);
