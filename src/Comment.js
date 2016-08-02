import React, { Component } from 'react';//コメント
class Comment extends Component {render() {
  var authorImage = {
    backgroundImage : "url(" + this.props.authorImage + ")"
  };
    return (
      <li>
        <span data-account={this.props.author} style={authorImage}></span><span><span>{this.props.author}<time>{this.props.date}</time></span>{this.props.comment}</span>
      </li>
    )
  }
}

//コメントリスト
export default class CommentList extends Component {render() {
    var commentNodes = this.props.data.map(function(comment) {
      return (
        <Comment author={comment.author} authorImage={comment.authorImage} date={comment.date} comment={comment.comment} key={comment.id} />
      )
    });
    return (
      <ul className="comment-list">
        {commentNodes}
      </ul>
    )
  }
}
