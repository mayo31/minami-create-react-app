import React, { Component } from 'react';

//タグ
class Tag extends Component {
  constructor(props) {
    super(props)
    this.handleAjax = this.handleAjax.bind(this);
  }
  handleAjax() {
    apiURL = './api/article?tag=' + this.refs.tag.name;
    $('.overlay').removeClass('overlay');
    $('#overlay').css('display', 'none');
    $('#overlay-close').css('display', 'none');
    $('#spinner').css('display', 'block');
  }
  render() {
    return (
      <li><a ref="tag" name={this.props.id} onClick={this.handleAjax}>{this.props.tag}</a></li>
    )
  }
}

//タグリスト
export default class TagList extends Component {render() {
    var tagNodes = this.props.data.map(function(tag) {
      return (
        <Tag tag={tag.name} id={tag.id} key={tag.id}/>
      )
    });
    return (
      <ul className="tag-list">
        {tagNodes}
      </ul>
    )
  }
}
