import React, { useEffect, useState } from "react";
import apis from "../api/apis";
import NavigationHeader from "../components/Header/NavigationHeader";
import { Container, Title, Box } from "../styles/styledComponent";
import styled from "styled-components";
import RoomInputs from "../components/Room/RoomInputs";
import BottomBtn from "../components/BottomBtn/BottomBtn";

const EditRoomPage = () => {
  const storeId = localStorage.getItem("storeId");
  const [roomList, setRoomList] = useState();
  const [editInfo, setEditInfo] = useState();
  const [postEdit, setPostEdit] = useState(false);
  const [update, setUpdate] = useState();

  const getRoomList = async () => {
    const result = await apis.getRooms(storeId);
    setRoomList(result?.data?.list);
  };

  useEffect(() => {
    getRoomList();
    setEditInfo();
  }, [update]);

  const onClickEdit = (e) => {
    setPostEdit(true);
  };

  return (
    <EditRoom>
      <NavigationHeader value="back" title={"방 수정"} storeId={storeId} />
      <div className="container">
        <Title color="#7d7d7d" size={15} bottom={20}>
          방을 선택해주세요.
        </Title>
        <div className="list">
          {roomList &&
            roomList?.map((room, k) => {
              const check = editInfo?.store_room_idx === room?.store_room_idx;
              return (
                <Room
                  key={k}
                  right={10}
                  onClick={() => setEditInfo(room)}
                  bg={check ? "#F335620D" : "#fff"}
                  bordercolor={check ? "#f33562" : "#e9e9e9"}
                >
                  <Title color={check ? "#f33562" : "#7d7d7d"}>
                    {room?.store_room_name}
                  </Title>
                </Room>
              );
            })}
        </div>
      </div>
      <RoomInputs
        onClick={onClickEdit}
        value={"edit"}
        editInfo={editInfo}
        postEdit={postEdit}
        storeId={storeId}
        setUpdate={setUpdate}
        setPostEdit={setPostEdit}
      />

      <BottomBtn title="수정하기" onClick={onClickEdit} />
    </EditRoom>
  );
};
const EditRoom = styled.div`
  & .container {
    padding: 20px;
    border-bottom: 1px solid #e9e9e9;
  }

  & .list {
    display: flex;
    flex-wrap: wrap;
    margin-top: 20px;
  }
`;

const Room = styled(Box)`
  padding: 15px;
`;
export default EditRoomPage;
