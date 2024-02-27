import React, { useEffect, useState } from "react";

import { Container, Title, Img } from "../styles/styledComponent";
import NavigationHeader from "../components/Header/NavigationHeader";
import styled from "styled-components";

import apis from "../api/apis";

import IconDelete from "../assets/image/delete_btn.png";
import RoomInputs from "../components/Room/RoomInputs";
import BottomBtn from "../components/BottomBtn/BottomBtn";

const AddRoomScreen = ({ navigation }) => {
  const storeId = localStorage.getItem("storeId");
  const [roomList, setRoomList] = useState();

  useEffect(() => {
    apis.getRooms(storeId).then((res) => {
      if (res.data.result === "000") {
        setRoomList(res.data.list);
      }
    });
  }, []);

  const onClickAddRoom = (addInfo) => {
    if (!addInfo?.store_room_name) {
      alert("입력 값을 확인해주세요.");
    } else {
      apis.putRooms(storeId, addInfo).then((res) => {
        if (res.data.result === "000") {
          setRoomList([...roomList, addInfo]);
        }
      });
    }
  };

  const onClickDelete = (data) => {
    apis
      .deleteRoom(data?.store_room_by_store_idx, data?.store_room_idx)
      .then((res) => {
        if (res.data.result === "000") {
          const filter = roomList.filter(
            (el) => el.store_room_idx !== data.store_room_idx
          );
          setRoomList(filter);
        }
      });
  };

  return (
    <Container>
      <NavigationHeader value="back" title={"방 추가"} storeId={storeId} />

      <RoomInputs onClick={onClickAddRoom} value={"add"} />

      <List>
        {roomList &&
          roomList?.map((room, k) => {
            const desc = !room.store_room_desc;
            return (
              <Item key={k}>
                <button onClick={() => onClickDelete(room)}>
                  <Img src={IconDelete} width={22} />
                </button>
                <Title>
                  {room.store_room_name} /{" "}
                  {desc ? "" : `${room.store_room_desc} / `}
                  {room.store_room_use_yn.toUpperCase()}
                </Title>
              </Item>
            );
          })}
      </List>

      {/* <BottomBtn title="적용하기" onPress={onClickAddRoom} /> */}
    </Container>
  );
};

const List = styled.div`
  padding: 20px 15px;
  overflow-y: auto;
`;

const Item = styled.div`
  margin-bottom: 10px;
  height: 25px;
  display: flex;
`;

export default AddRoomScreen;
