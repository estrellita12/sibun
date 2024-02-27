import React, { useEffect, useState } from "react";

import { Title } from "../../styles/styledComponent";
import CheckBox from "../CheckBox/CheckBox";
import styled from "styled-components";

import apis from "../../api/apis";

const RoomInputs = ({
  onClick,
  value,
  editInfo,
  postEdit,
  storeId,
  setUpdate,
  setPostEdit,
}) => {
  const CheckValue = value === "edit";

  const [addInfo, setAddInfo] = useState({
    store_room_name: "",
    store_room_desc: "",
    store_room_orderby: "",
    store_room_use_yn: "",
  });

  const {
    store_room_name,
    store_room_desc,
    store_room_orderby,
    store_room_use_yn,
  } = addInfo;
  const data = [
    { title: "Y", value: "y" },
    { title: "N", value: "n" },
  ];

  const onClickCheck = (e) => {
    onChange("store_room_use_yn", e);
  };

  const onChange = (keyvalue, e) => {
    setAddInfo({
      ...addInfo,
      [keyvalue]: e,
    });
  };

  function postEditFuc() {
    apis.postRooms(storeId, editInfo?.store_room_idx, addInfo).then((res) => {
      console.log(res.data);
      if (res.data.result === "000") {
        alert("수정이 완료되었습니다!");
        setAddInfo({
          store_room_name: "",
          store_room_desc: "",
          store_room_use_yn: "",
        });
        setUpdate(editInfo?.store_room_idx);
        setPostEdit(false);
      }
    });
  }

  useEffect(() => {
    if (CheckValue) {
      setAddInfo({
        store_room_name: editInfo?.store_room_name,
        store_room_desc: editInfo?.store_room_desc,
        store_room_use_yn: editInfo?.store_room_use_yn,
      });
      setUpdate();
    }
  }, [editInfo?.store_room_idx]);

  useEffect(() => {
    if (postEdit) {
      postEditFuc();
    }
  }, [postEdit]);

  return (
    <Room>
      <div className="line">
        <span>방이름</span>
        <input
          className="input"
          placeholder="방이름을 입력해주세요."
          onChange={(e) => onChange("store_room_name", e.target.value)}
          name="store_room_name"
          value={store_room_name || ""}
        />
      </div>
      <div className="line">
        <span>상세설명</span>
        <input
          className="input"
          placeholder="상세설명을 입력해주세요."
          onChange={(e) => onChange("store_room_desc", e.target.value)}
          name="store_room_desc"
          value={store_room_desc || ""}
        />
      </div>
      <div className="line">
        <span>사용여부</span>
        <div className="checkwrap">
          {data.map((item, k) => {
            return (
              <CheckBoxWrap key={k}>
                <CheckBox
                  onClickCheck={onClickCheck}
                  id={item?.value}
                  isCheck={store_room_use_yn === item.value}
                  width={20}
                />
                <Title size={18} left={2}>
                  {item.title}
                </Title>
              </CheckBoxWrap>
            );
          })}
        </div>
      </div>
      {value === "edit" ? (
        ""
      ) : (
        <button className="addbtn" onClick={() => onClick(addInfo)}>
          + 방 추가하기
        </button>
      )}
    </Room>
  );
};

const Room = styled.div`
  max-width: 500px;
  & .line {
    height: 60px;
    padding: 0px 20px;
    border-bottom: 1px solid #e9e9e9;
    display: grid;
    align-items: center;
    grid-template-columns: 25% 75%;
  }

  & .input {
    border: none;
  }

  & .checkwrap {
    display: flex;
  }

  & .addbtn {
    width: 100%;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 15px solid #d7d7d7;
  }
`;

const CheckBoxWrap = styled.div`
  margin-right: 20px;
  display: flex;
  flex-direction: row;
`;

export default RoomInputs;
