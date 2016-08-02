import React, { Component } from 'react';

//タグ選択コンポーネント
class TagCheckList extends Component {render() {
    return (
    <li>
		<input type="checkbox" name="tag-check" id={this.props.tagName} />
		<label htmlFor={this.props.tagName}>{this.props.tagName}</label>
	</li>
    );
  }
}

//タグ選択コンポーネント
class TagCheckArea extends Component {render() {
    var tagCheckNodes= this.props.data.map(function(tagCheck) {
      return (
        <TagCheckList key={tagCheck.id} tagName={tagCheck.name} />
      )
    });
    return (
    <ul id="tag-check" className="check-list paragraph">
      {tagCheckNodes}
    </ul>
    )
  }
}

//新規タグコンポーネント
class NewTag extends Component {render(){
    return (
      <li>
        <input type="checkbox" name="tag-check" id={this.props.tagName} data-tag={this.props.tagName} defaultChecked="checked" />
		<label htmlFor={this.props.tagName}>{this.props.tagName}</label>
      </li>
    );
  }
}

//新規タグリストコンポーネント
class TagInputList extends Component {render(){
    var TagNodes = this.props.tagData.map(function(tag, index){
      return (
        <NewTag tagName={tag.tagName} key={index}/>
      );
    });
    return (
      <ul id="tag-input-list" className="check-list">
          {TagNodes}
      </ul>
    );
  }
}

//新規タグ入力フォームコンポーネント
class TagForm extends Component {
  constructor(props) {
    super(props)
    this.state = {
      tagData: []
    };
	this.handleSubmit = this.handleSubmit.bind(this);
	this.handleEnterSubmit = this.handleEnterSubmit.bind(this);
  }
  handleSubmit(e){
    e.preventDefault();
	var t = this;
    var tagName = $('#tag-input').val();
    tagName = tagName.replace(/\s+/g, "");
	var already = '#' + tagName;
    if (!tagName){
      return;
    }
    else if($(already).length > 0){
      $(already).prop('checked',true);
    }
    else{
		var data = t.state.tagData;
		data.push({tagName: tagName});
		t.setState({tagData: data});
    };
	$('#tag-input').val('');
  }
  handleEnterSubmit(e){
    e.stopPropagation();
	var t = this;
    var tagName = $('#tag-input').val();
    tagName = tagName.replace(/\s+/g, "");
	var already = '#' + tagName;
    if (e.which === 13 && tagName !== '') {
      if (!tagName){
        return;
      }
	  else if($(already).length > 0){
	    $(already).prop('checked',true);
      }
      else{
		var data = t.state.tagData;
		data.push({tagName: tagName});
		t.setState({tagData: data});
      };
      $('#tag-input').val('');
      e.preventDefault();
    }
    else if (e.which === 13) {
      e.preventDefault();
    }
  }
  tagDataReset(){
	var t = this;
	this.setState({tagData: []});	  
  }
  render(){
    return (
      <div id="tag-input-area" className="box">
        <TagInputList tagData={this.state.tagData} />
        <input type="text" id="tag-input" placeholder="タグ" onKeyDown={this.handleEnterSubmit} />
        <button onClick={this.handleSubmit}>追加</button>
        <TagCheckArea data={this.props.data} />
      </div>
    );
  }
}

//URLフォームコンポーネント
export default class URLForm extends Component {
  constructor(props) {
    super(props)
	this.handleSubmit = this.handleSubmit.bind(this);
  }
  handleSubmit(e){
	var t = this;
    $('#spinner').css('display', 'block');
    e.preventDefault();
    inputURL = $('#url-input').val();
    if(inputURL===''){
      $('#spinner').css('display', 'none');
      alert('URLを入力してください');
	  return;
    };
    tagArr = [];
    $.each($('[name="tag-check"]'),function(){
      if($(this).is(':checked')){
        tagArr.push($(this).attr('id'));
      }
    });
    $.ajax({
      url: './api/article',
      type: 'POST',
      data: {"url": inputURL, "tag": tagArr},
      timeout:30000,
      })
      .done(function() {
        $('#spinner').css('display', 'none');
        $('#url-input').val('');
        $('[name="tag-check"]').prop('checked', false);
        apiURL = 'post';
        tagBool = true;
      })
      .fail(function(xhr, status, err) {
        $('#spinner').css('display', 'none');
        alert(err);
      });
	this.refs.tagForm.tagDataReset();
  }
  render(){
    return(
    <form id="form-url-input">
      <input type="url" placeholder="URL" id="url-input" />
	  <input type="submit" id="url-submit" className="button01" onClick={this.handleSubmit} />
      <TagForm data={this.props.data} ref="tagForm" />
    </form>
    );
  }
}
