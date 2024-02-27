import React, { useEffect, useState } from "react";
import styled from "styled-components";
import apis from "../api/apis";
import { Img, Container } from "../styles/styledComponent";
import IconAdd from "../assets/image/store_add.png";
import IconSmile from "../assets/image/smile.png";
import CheckBox from "../components/CheckBox/CheckBox";
import { useNavigate } from "react-router-dom";
import BottomBtn from "../components/BottomBtn/BottomBtn";

const StorelistPage = () => {
  const navigate = useNavigate();
  const colors = ["salmon", "lightgreen", "pink", "skyblue"];

  const [storeLists, setStoreList] = useState();
  const [checkBox, setCheckBox] = useState(false);
  const [deletStore, setDelteStore] = useState(null);
  const [update, setUpdate] = useState(0);

  useEffect(() => {
    apis.getStore().then((res) => {
      if (res?.data?.result === "000") {
        const data = res.data.list;
        const filter = data === undefined ? ["last"] : [...data, "last"];
        setStoreList(filter);
      }
    });
  }, [update]);

  const onClickCheck = (id) => {
    const arr = [];
    if (!deletStore) {
      arr.push(id);
    } else {
      if (deletStore.includes(id)) {
        const result = deletStore.filter((el) => el !== id);
        arr.push(...result);
      } else {
        arr.push(...deletStore, id);
      }
    }
    setDelteStore(arr);
  };

  const onClickEdit = () => {
    setCheckBox(!checkBox);
    if (!checkBox) {
      setDelteStore(null);
    }
  };

  const onClickStore = (store) => {
    if (store === "last") {
      navigate("/addstore");
    } else if (checkBox) {
      onClickCheck(store?.store_idx);
    } else {
      const time = {
        open: store?.store_open_time,
        close: store?.store_close_time,
      };
      navigate(`/reservation/${store?.store_idx}`);
      localStorage.setItem("storeId", store?.store_idx);
      localStorage.setItem("storeInfo", JSON.stringify(time));
    }
  };

  const onClickDelete = async () => {
    const arr = [];
    await deletStore.map((i, k) => {
      apis.deleteStore(i).then((res) => {
        if (res.data.result === "000") {
          arr.push(k);
          if (k + 1 === deletStore?.length) {
            alert("삭제하였습니다.");
            setDelteStore(null);
            setCheckBox(false);
            setUpdate(update + 1);
          }
        }
      });
    });
  };

  return (
    <Wrap>
      <div className="colum">
        <h1>매장선택</h1>
        <span>관리할 매장을 선택해주세요.</span>
        <EditBtn onClick={() => onClickEdit()}>
          {checkBox ? "취소" : "편집"}
        </EditBtn>
      </div>

      <div className="lists">
        {storeLists?.map((store, key) => {
          const imgvalue = !!store?.store_main_simg;
          const randomNum = Math.floor(Math.random() * 3 + 1);
          return (
            <div className="store" key={key}>
              {checkBox ? (
                <Position>
                  <CheckBox
                    onClickCheck={onClickCheck}
                    id={store?.store_idx}
                    isCheck={deletStore?.includes(store?.store_idx)}
                  />
                </Position>
              ) : (
                ""
              )}{" "}
              <button
                className="colum store"
                onClick={() => onClickStore(store)}
              >
                <MainImg
                  bg={
                    imgvalue || store === "last" ? "#ffff" : colors[randomNum]
                  }
                >
                  {store === "last" ? (
                    <Img src={IconAdd} width={150} />
                  ) : (
                    <Img
                      src={imgvalue ? store?.store_main_simg : IconSmile}
                      fit={imgvalue ? "cover" : "contain"}
                      width={imgvalue ? 150 : 100}
                      radius={8}
                    />
                  )}
                </MainImg>
                <p> {store?.store_name}</p>
              </button>
            </div>
          );
        })}
      </div>

      {checkBox ? (
        <BottomBtn
          title={`삭제하기 ${!deletStore?.length ? "" : deletStore?.length}`}
          onClick={onClickDelete}
        />
      ) : (
        ""
      )}
    </Wrap>
  );
};

const Wrap = styled(Container)`
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #fff;

  & .colum {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    position: relative;
  }

  & .store {
    margin: 20px 0px;
    position: relative;
    z-index: 200;
  }
  & .lists {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 10px 40px;
    margin-top: 50px;
    padding-bottom: 60px;
    z-index: 10;
  }

  & .store {
    position: relative;
  }
`;

const MainImg = styled.div`
  width: 150px;
  height: 150px;
  border-radius: 5px;
  background-color: ${(props) => props.bg};
  display: flex;
  align-items: center;
  justify-content: center;
`;

const EditBtn = styled.button`
  position: absolute;
  right: -80px;
  top: 30px;
  font-size: 16px;
`;

const Position = styled.div`
  position: absolute;
  top: 10px;
  left: -5px;
  z-index: 1000;
`;
export default StorelistPage;
