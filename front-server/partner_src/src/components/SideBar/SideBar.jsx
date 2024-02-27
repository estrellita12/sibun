import React, { useEffect, useState } from "react";
import styled from "styled-components";
import { Img, Title } from "../../styles/styledComponent";

import IconCheck from "../../assets/image/drawer_check.png";
import IconNoneimg from "../../assets/image/noneImg.png";
import IconSetting from "../../assets/image/setting.png";
import IconDown from "../../assets/image/down.png";
import IconDownG from "../../assets/image/down_g.png";
import IconUp from "../../assets/image/up.png";
import IconClose from "../../assets/image/nav_close.png";
import axios from "axios";
import apis from "../../api/apis";

import { useNavigate } from "react-router-dom";

const SideBar = ({ setOpenNav }) => {
  const navigate = useNavigate();
  const storeId = localStorage.getItem("storeId");
  const [store, setStore] = useState();
  const [selectedStore, setSelectedStore] = useState();
  const [user, setUser] = useState();
  const [openList, setOpenList] = useState(false);
  const [openDropdown, setOpenDropdown] = useState(false);
  const [selectedId, setSelectedId] = useState();

  const menuList = [
    { title: "예약관리", screen: `/reservation/${selectedId}` },
    { title: "스케줄관리", screen: "/schedule" },
    { title: "후기관리", screen: "/review" },
    { title: "할인권 관리", screen: "/voucher" },
    { title: "나의 매장 관리", screen: "" },
  ];

  const managementList = [
    { title: "기본 정보 수정", screen: "/eidtstore" },
    { title: "이용 가격 수정", screen: "/editprice" },
    { title: "이미지 수정", screen: "/editimg" },
    { title: "방 추가", screen: "/addroom" },
    { title: "방 수정", screen: "/editroom" },
  ];

  function getAllData() {
    axios.all([apis.getUser(), apis.getStore()]).then(
      axios.spread((res1, res2) => {
        setUser(res1.data.data);
        const storelist = res2.data.list;
        if (res2.data.result === "000") {
          const filterSelected = storelist.filter(
            (el) => el.store_idx === Number(storeId)
          );
          const anotherStore = storelist.filter(
            (el) => el.store_idx !== Number(storeId)
          );

          setSelectedStore(...filterSelected);
          setStore(anotherStore);
        }
      })
    );
  }

  useEffect(() => {
    getAllData();
  }, [selectedId]);

  const onClickStore = (e) => {
    setOpenDropdown(false);
    setSelectedId(e);
    localStorage.setItem("storeId", e);
  };

  const onClickAdd = (value) => {
    const checkValue = value === "edit";

    checkValue ? setOpenList(false) : setOpenDropdown(false);

    setOpenDropdown(false);

    navigate("/addstore");
  };

  const onClickMovePage = (screen, index) => {
    if (index === 4) {
      setOpenList(!openList);
    } else {
      navigate(screen);
    }
  };

  return (
    <SideNavBar>
      {selectedStore && (
        <div className="nav">
          <button className="close" onClick={() => navigate(-1)}>
            <Img src={IconClose} width={35} />
          </button>

          {user && (
            <div className="top">
              <button className="flex" onClick={() => navigate("/edituser")}>
                <Img width={22} src={IconSetting} />
                정보수정
              </button>
              <div className="profile">
                <Profile
                  src={!user?.pt_profile_img ? null : user?.pt_profile_img}
                />
                <Title color="#fff" size={20} weight={600}>
                  {user?.pt_name}
                </Title>
                <Title color="#747474">{user?.pt_cellphone}</Title>
              </div>
            </div>
          )}

          <div className="bottom">
            <div className="selectedstore">
              {selectedStore && (
                <div className="seletedInfo">
                  <div className="flex">
                    <Img
                      width={50}
                      radius={10}
                      fit="cover"
                      src={
                        !!selectedStore?.store_main_simg
                          ? selectedStore?.store_main_simg
                          : IconNoneimg
                      }
                    />
                    <Title left={5} color="#222" weight={600} size={15}>
                      {selectedStore.store_name}
                    </Title>
                  </div>
                  <Img width={22} src={IconCheck} />
                </div>
              )}

              {store &&
                (store?.length >= 1 ? (
                  <div className="dropdown">
                    <DropdowBox onClick={() => setOpenDropdown(!openDropdown)}>
                      <Title color="#444" size={14}>
                        다른 매장
                      </Title>
                      <Img width={13} src={IconDown} />
                    </DropdowBox>

                    {openDropdown ? (
                      <div className="storelist">
                        <Dropdown>
                          {store?.map((i, k) => {
                            return (
                              <button
                                className="storebtn"
                                key={k}
                                onClick={() => onClickStore(i.store_idx)}
                              >
                                <Img
                                  width={50}
                                  radius={10}
                                  right={10}
                                  src={
                                    !!i?.store_main_simg
                                      ? i?.store_main_simg
                                      : IconNoneimg
                                  }
                                  fit="cover"
                                />

                                {i.store_name}
                              </button>
                            );
                          })}
                        </Dropdown>
                        <AddStore margin="auto" onClick={() => onClickAdd()}>
                          + 매장 추가하기
                        </AddStore>
                      </div>
                    ) : (
                      ""
                    )}
                  </div>
                ) : (
                  <AddStore
                    bg="#e9e9e9"
                    margin="10px auto 20px auto"
                    onClick={() => onClickAdd()}
                  >
                    + 매장 추가하기
                  </AddStore>
                ))}
            </div>

            <div className="menulist">
              {menuList.map((i, k) => {
                return (
                  <button
                    className="menubtn"
                    key={k}
                    onClick={() => onClickMovePage(i.screen, k)}
                  >
                    <Title size={15}>{i.title}</Title>
                    {k === 4 ? (
                      <Img width={13} src={openList ? IconUp : IconDownG} />
                    ) : (
                      ""
                    )}
                  </button>
                );
              })}

              {openList ? (
                <div className="managementList">
                  {managementList.map((i, k) => {
                    return (
                      <button
                        className="managementBtn"
                        onClick={() => {
                          i.screen === "EditStroeScreen"
                            ? onClickAdd("edit")
                            : navigate(i.screen);
                        }}
                        key={k}
                      >
                        {i.title}
                      </button>
                    );
                  })}
                </div>
              ) : (
                ""
              )}
            </div>
          </div>
        </div>
      )}
    </SideNavBar>
  );
};

const SideNavBar = styled.div`
  box-sizing: border-box;
  position: relative;
  width: 100%;
  height: 100vh;

  & .nav {
    width: 100%;
    height: 100%;
    background-color: #fff;
  }

  & .close {
    position: absolute;
    right: 10px;
    top: 20px;
  }

  & .top {
    background-color: #222;
    padding: 10px;
    height: 180px;
    /* border-top-right-radius: 30px; */
  }

  & .flex {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: #fff;
  }
  & .profile {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  & .selectedstore {
    width: 100%;
    background-color: #fff;
    padding: 0px 20px;
    box-sizing: border-box;
    position: relative;
    margin-bottom: 30px;
  }

  & .bottom {
    background-color: #fff;
    height: 100%;
  }

  & .seletedInfo {
    padding-top: 20px;
    display: grid;
    align-items: center;
    grid-template-columns: 90% 10%;
  }

  & .flex {
    display: flex;
  }

  & .dropdown {
    position: relative;
    width: 100%;
    margin-top: 30px;
  }

  & .storelist {
    height: 300px;
    background-color: #fff;
    border-left: 1px solid #e9e9e9;
    border-right: 1px solid #e9e9e9;
    border-bottom: 1px solid #e9e9e9;
    position: absolute;
    width: 100%;
    box-sizing: border-box;
  }

  & .storebtn {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    font-size: 15px;
    color: #222;
  }

  & .menulist {
    display: flex;
    flex-direction: column;
    background-color: #fff;
    border-top: 10px solid #f3f3f3;
    overflow-x: auto;
  }

  & .menubtn {
    padding: 0px 20px;
    height: 50px;

    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f3f3f3;
  }

  & .managementList {
    display: flex;
    flex-direction: column;
    height: 140px;
    overflow-x: auto;
    padding: 10px 30px;
  }

  & .managementBtn {
    font-size: 14px;
    color: #7d7d7d;
    margin-bottom: 10px;
  }
`;

const Profile = styled.img`
  width: 65px;
  height: 65px;
  border-radius: 50px;
  background-color: #d9d9d9;
  margin-bottom: 15px;
`;

const DropdowBox = styled.button`
  position: relative;
  width: 100%;
  height: 38px;
  border: 1px solid #e9e9e9;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  padding: 0px 10px;
  box-sizing: border-box;
`;

const Dropdown = styled.div`
  display: flex;
  flex-direction: column;
  overflow-x: scroll;
  width: 100%;
  height: 250px;
  padding: 10px;
  box-sizing: border-box;
`;

const AddStore = styled.button`
  width: 100%;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: ${(props) => props.bg || "#FFF"};
  margin: ${(props) => props.margin};

  color: #444;
  font-size: 15px;

  position: sticky;
  bottom: 0px;
`;
export default SideBar;
