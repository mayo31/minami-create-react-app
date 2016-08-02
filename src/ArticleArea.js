import React, { Component } from 'react';
import TagList from './Tag';
import CommentList from './Comment';

//記事削除ボタン
class ArticleDelete extends Component {
  constructor(props) {
    super(props)
	this.handleDelete = this.handleDelete.bind(this);
  }
  handleDelete() {
    $('#spinner').css('display', 'block');
    deleteURL = './api/article/' + this.props.articleID;
    $.ajax({
      type: 'delete',
      url: deleteURL,
      cache: false,
    }).done(function() {
      $('.overlay').removeClass('overlay');
      tmpURL = '';
      $('#overlay').css('display', 'none');
      $('#overlay-close').css('display', 'none');
    }).fail(function(xhr, status, err) {
      console.error(deleteURL, status, err.toString());
      $('.overlay').removeClass('overlay');
      $('#spinner').css('display', 'none');
      $('#overlay').css('display', 'none');
      $('#overlay-close').css('display', 'none');
    });
  }
  render() {
    return (
    <button className="button02" onClick={this.handleDelete}>この記事を削除する</button>
    );
  }
}

//コメント入力欄
class CommentInput extends Component {
  constructor(props) {
    super(props)
	this.handleCommentSubmit = this.handleCommentSubmit.bind(this);
  }
  handleCommentSubmit(){
    $('#spinner').css('display', 'block');
    var articleID = this.props.articleID;
    var comment = this.refs.comment.value;
    $.ajax({
      url: '/api/comment',
      type: 'POST',
      data: {"article_id": articleID, "user_id": loggedin, "comment": comment},
      timeout:10000,
    }).done(function() {
      articleBool = true;
    }).fail(function(xhr, status, err) {
      console.error('/api/comment', status, err.toString());
    });
    this.refs.comment.value = "";
  }
  render() {
    return (
      <form className="comment-input" id={'comment-input' + this.props.articleID}>
        <textarea placeholder="コメント" ref="comment"></textarea>
        <button type="button" className="button01" onClick={this.handleCommentSubmit}>コメントを送信</button>
      </form>
    )
  }
}

//記事コンポーネント
class ArticleList extends Component {render() {
    var articleImage = {
      backgroundImage : "url(" + this.props.articleImage + ")"
    };
    var accountImage = {
      backgroundImage : "url(" + this.props.userIcon + ")"
    };
    return (
    <li>
      <article>
        <p className="author"><span data-account={this.props.userName} style={accountImage}></span><span>{this.props.userName}</span></p>
        <a href={this.props.articleUrl} target="_blank" style={articleImage}>
          <h2>{this.props.articleTitle}</h2>
          <p>{this.props.articleDescription}</p>
        </a>
        <TagList data={this.props.articleTag} />
        <h3>コメント</h3>
        <CommentList data={this.props.articleComment} />
        {(() => {
          if (loggedin) {
            return <CommentInput articleID={this.props.articleID} />;
          }
        })()}
        {(() => {
          if (loggedin === this.props.userId) {
            return <ArticleDelete articleID={this.props.articleID}/>;
          }
        })()}
      </article>
    </li>
    );
  }
}

//記事リストコンポーネント
export default class ArticleArea extends Component {
  //JSONデータ取得
  constructor(props) {
    super(props)
    this.state = {
      data: []
    };
    this.fetchURL = this.fetchURL.bind(this);
    this.loadArticleFromServer = this.loadArticleFromServer.bind(this);
    this.componentDidMount = this.componentDidMount.bind(this);
  }
  fetchURL() {
    if(apiURL === 'post'){
      apiURL = './api/article';
      tmpURL = apiURL;
      this.loadArticleFromServer();
    }
    else if(apiURL !== tmpURL){
      tmpURL = apiURL;
      this.loadArticleFromServer();
    }
    else if(articleBool === true){
      this.loadArticleFromServer();
    }
  }
  loadArticleFromServer() {
    var t = this;
    $.ajax({
      url: apiURL,
      dataType: 'json',
      cache: false,
      timeout: 10000
    }).done(function(data) {
      t.setState({data: data});
      $('#spinner').css('display', 'none');
      $('#menu00').prop('checked', true);
    }).fail(function(xhr, status, err) {
      $('#spinner').css('display', 'none');
      alert(apiURL, status, err.toString());
    });
    articleBool = false;
  }
  componentDidMount() {
    this.loadArticleFromServer();
    setInterval(this.fetchURL, 1000);
  }
  render() {
    var articleNodes = this.state.data.map(function(article) {
      return (
        <ArticleList userId={article.userId} userName={article.userName} userIcon={article.userIcon} articleID={article.articleID} articleTitle={article.articleTitle} articleUrl={article.articleUrl} articleDescription={article.articleDescription} articleImage={article.articleImage} articleTag={article.tagData} articleComment={article.commentData} key={article.articleID}/>
      )
    });
    return (
      <ul id="article-list" className="wrap">
        {articleNodes}
      </ul>
    )
  }
}
