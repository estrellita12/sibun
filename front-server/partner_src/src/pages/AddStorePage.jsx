import React, { useEffect, useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import axios from "axios";

import dayjs from "dayjs";
import apis from "../api/apis";
import { styled } from "styled-components";

import BasicTimePicker from "../components/TimePicker/BasicTimePicker";
import Option from "../components/Options/Option";
import BottomBtn from "../components/BottomBtn/BottomBtn";
import StorePrice from "../components/Addstore/StorePrice";
import NavigationHeader from "../components/Header/NavigationHeader";
import ImgPicker from "../components/ImgPicker/ImgPicker";
import WeekDay from "../components/Week/WeekDay";
import KakaoAddress from "../components/KakaoAddress/KakaoAddress";

import IconClose from "../assets/image/close_g.png";
import IconAdd from "../assets/image/addImg.png";

import { Time } from "../utils/TimeCalculation";
import { ChangeTimeData } from "../utils/TimeCalculation";
import { Input, Img, Title } from "../styles/styledComponent";

const AddStorePage = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const eidtvalue = location.pathname === "/eidtstore";
  const storeId = localStorage.getItem("storeId");
  const [day, setDay] = useState([]);
  const [startTime, setStartTime] = useState(dayjs().format("HH:mm"));
  const [endTime, setEndTime] = useState(dayjs().format("HH:mm"));
  const [modalVisible, setModalVisible] = useState(false);
  const [img, setImg] = useState();
  const [openaddr, setOpenaddr] = useState(false);
  const [storeInfo, setStoreInfo] = useState({
    store_ctg_idx: 2,
    store_name: "",
    store_tel: "",
    store_room_cnt: 1,
    store_amenities: "",
    store_addr1: "",
    store_addr2: "",
  });

  const options = [
    "스윙/무비",
    "바닥스크린",
    "새벽영업",
    "장비보관",
    "주차",
    "단체석",
    "무선인터넷",
    "제로페이",
    "락커룸",
    "샤워장",
    "지역화페",
    "국민지원금",
  ];
  const [optionData, setOptionData] = useState();
  const [selectedOption, setSelectedOption] = useState(null);
  const { store_name, store_tel, store_room_cnt, store_addr2, store_addr1 } =
    storeInfo;

  function MainData(main) {
    setStoreInfo({
      store_addr1: main.store_addr1,
      store_addr2: main.store_addr2,
      store_name: main.store_name,
      store_tel: main.store_tel,
    });

    setStartTime(Time(main?.store_open_time));
    setEndTime(Time(main?.store_close_time));
    const days = main.store_closed_days.split(",");
    setDay(days);
    const optionList = main.store_amenities.split(",");
    setOptionData(optionList);
  }

  useEffect(() => {
    if (eidtvalue) {
      axios.all([apis.getStoreDetail(storeId), apis.getStoreImg(storeId)]).then(
        axios.spread((res1, res2) => {
          const main = res1.data.data;
          const detail = res2.data.list;
          MainData(main);
          const mainImg = {
            store_img_data: main?.store_main_simg,
            uuid: "main",
          };

          const imgdata = !detail ? [mainImg] : [mainImg, ...detail];
          setImg(imgdata);
        })
      );
    } else {
      setOptionData(options);
    }
  }, []);

  const onChange = (keyvalue, e) => {
    setStoreInfo({
      ...storeInfo,
      [keyvalue]: e,
    });
  };

  const onChangeImg = (event) => {
    const imageLists = event?.target?.files;
    let imageUrlLists = !img ? [] : [...img];
    for (let i = 0; i < imageLists.length; i++) {
      const currentImageUrl = URL.createObjectURL(imageLists[i]);
      imageUrlLists.push({
        file: imageLists[i],
        url: currentImageUrl,
        id: !img ? i : img.length + i + 1,
      });
    }

    setImg(imageUrlLists);
  };

  function Base64(el, idx) {
    const arr = {};

    const file = el.file;
    const reader = new FileReader();
    reader.onload = (base64) => {
      const image = new Image();
      image.src = base64.target.result;
      image.onload = (e) => {
        const maxSize = 500;
        var canvas = document.createElement("canvas"),
          width = image.width,
          height = image.height;
        if (width > height) {
          if (width > maxSize) {
            height *= maxSize / width;
            width = maxSize;
          }
        } else {
          if (height > maxSize) {
            width *= maxSize / height;
            height = maxSize;
          }
        }
        canvas.width = width;
        canvas.height = height;
        canvas.getContext("2d").drawImage(image, 0, 0, width, height);
        arr.store_img_data = canvas.toDataURL(`image/jpeg`, 0.5);
        arr.store_img_orderby = idx;
      };
    };
    reader.readAsDataURL(file);
    return arr;
  }

  function editDetailImg(detailImg) {
    const data = { store_img_li: detailImg };

    apis.postBulkImg(storeId, data).then((res) => {
      console.log("상세이미지", res);
    });
  }

  async function makeData(result) {
    const today = dayjs().format("YYYY-MM-DD");
    const storeAddr = !store_addr2
      ? `${storeInfo?.store_addr1}`
      : `${storeInfo?.store_addr1} ${store_addr2}`;
    storeInfo.store_addr = storeAddr;
    storeInfo.store_open_time = ChangeTimeData(`${today} ${startTime}`);
    storeInfo.store_close_time = ChangeTimeData(`${today} ${endTime}`);
    storeInfo.store_closed_days = day.join(",");
    storeInfo.store_amenities = selectedOption?.join(",");

    if (eidtvalue) {
      storeInfo.store_main_simg = result[0].store_img_data;
      const detailImg = result.slice(1);
      console.log(detailImg);
      editDetailImg(detailImg);
      await apis.postStore(storeId, storeInfo).then((res) => {
        console.log("기본정보", res);
      });
    } else {
      storeInfo.store_main_simg = !!img ? result[0].store_img_data : "";

      const detailImg = result.slice(1);
      storeInfo.store_img_li = !!img ? [...detailImg] : "";
      await apis.putStore(storeInfo).then((res) => {
        console.log("store", res);
        if (res.data.result === "000") {
          navigate("/storelist");
        }
      });
    }
  }

  const onClickAdd = async () => {
    const arr = [];
    const filterNew = img.map((el, index) => {
      console.log(index);
      if (!!el.store_img_data) {
        arr.push({
          store_img_data: el.store_img_data,
          store_img_orderby: Number(index) - 1,
        });
      } else {
        arr.push(Base64(el, index - 1));
      }
    });
    setTimeout(() => makeData(arr), 1500);
  };

  return (
    <AddStore>
      {openaddr ? (
        <div className="addr">
          <KakaoAddress
            setOpenaddr={setOpenaddr}
            setStoreInfo={setStoreInfo}
            storeInfo={storeInfo}
          />
        </div>
      ) : (
        ""
      )}

      {optionData && (
        <>
          <NavigationHeader value="back" title="나의 매장" />
          <div className="form">
            <div className="padding">
              <div className="wrap">
                <span className="title">
                  업체명
                  <Title color="#f33562" size="16" weight="600">
                    {" "}
                    *
                  </Title>
                </span>
                <Input
                  onChange={(e) => onChange("store_name", e.target.value)}
                  name="store_name"
                  value={store_name || ""}
                />
              </div>

              <div className="adressTitle">
                <span className="title">
                  주소지
                  <Title color="#f33562" size="16" weight="600">
                    {" "}
                    *
                  </Title>
                </span>
                {/* <button className="btn" onClick={() => navigate("/kakaoadress")}> */}
                <button className="btn" onClick={() => setOpenaddr(true)}>
                  주소 검색
                </button>
              </div>
              <div>
                <Input
                  placeholder="주소"
                  name="store_addr1"
                  bottom={7}
                  value={store_addr1 || ""}
                />
                <Input
                  placeholder="상세주소"
                  onChange={(e) => onChange("store_addr2", e.target.value)}
                  name="store_addr2 "
                  value={store_addr2 || ""}
                />
              </div>
              <div className="wrap">
                <span className="title">
                  연락처
                  <Title color="#f33562" size="16" weight="600">
                    {" "}
                    *
                  </Title>
                </span>
                <div className="telwrap">
                  <Input
                    onChange={(e) => onChange("store_tel", e.target.value)}
                    name="store_tel"
                    value={store_tel || ""}
                  />
                  <button
                    onClick={() => onChange("store_tel", "")}
                    className="closebtn"
                  >
                    <Img src={IconClose} width={10} />
                  </button>
                </div>
              </div>
              {eidtvalue ? (
                ""
              ) : (
                <div className="wrap">
                  <StorePrice getData={onChange} />
                </div>
              )}

              <div className="wrap">
                <span className="title">이미지</span>
                <div className="imglist">
                  <>
                    <label htmlFor="profileImg">
                      <Img src={IconAdd} width={100} />
                    </label>
                    <input
                      type="file"
                      multiple
                      accept="image/*"
                      id="profileImg"
                      style={{ display: "none" }}
                      onChange={onChangeImg}
                    />
                  </>

                  {img && (
                    <ImgPicker img={img} setImg={setImg} storeId={storeId} />
                  )}
                </div>
              </div>
              <div className="wrap">
                <div className="titlewrap">
                  <span className="title">
                    부가서비스
                    <Title left={5} color="#7d7d7d" size={12} weight={400}>
                      중복선택가능
                    </Title>
                  </span>
                  <button
                    className="addbtn"
                    onClick={() => setModalVisible(true)}
                  >
                    +추가
                  </button>
                </div>
                {optionData && (
                  <Option
                    getData={onChange}
                    modalVisible={modalVisible}
                    setModalVisible={setModalVisible}
                    optionData={optionData}
                    setOptionData={setOptionData}
                    setSelectedOption={setSelectedOption}
                    selectedOption={selectedOption}
                    value={eidtvalue ? "edit" : "add"}
                  />
                )}
              </div>
              <div className="wrap">
                <span className="title bottomNone">영업시간</span>
                <div className="between">
                  <BasicTimePicker setTime={setStartTime} time={startTime} />
                  <Title left={10} right={10}>
                    -
                  </Title>
                  <BasicTimePicker
                    setTime={setEndTime}
                    time={endTime}
                    className="left"
                  />
                </div>
              </div>
              <div className="wrap">
                <span className="title bottomNone">휴무선택</span>
                <WeekDay day={day} setDay={setDay} />
              </div>

              {eidtvalue ? (
                ""
              ) : (
                <div className="wrap">
                  <span className="title">방 개수</span>
                  <div>
                    <Input
                      width={20}
                      onChange={(e) =>
                        onChange("store_room_cnt", e.target.value)
                      }
                      name="store_room_cnt"
                      value={store_room_cnt || ""}
                    />
                    <Title color="#7d7d7d" size={14} className="cnt">
                      개
                    </Title>
                  </div>
                </div>
              )}
            </div>
            <BottomBtn title="완료" onClick={onClickAdd} />
          </div>
        </>
      )}
    </AddStore>
  );
};
const AddStore = styled.div`
  & .addr {
    position: fixed;
    top: 0px;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 500;
    background-color: #ececec;
  }
  & .form {
    box-sizing: border-box;
  }

  & .padding {
    overflow-y: scroll;
    padding: 80px 20px 100px 20px;
    height: calc(100vh- 250px);
  }

  & .wrap {
    display: flex;
    flex-direction: column;
    margin-top: 20px;
  }

  & .title {
    font-size: 17px;
    font-weight: 600;
    margin-bottom: 12px;
  }

  & .adressTitle {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    & .btn {
      border-bottom: 1px solid #7d7d7d;
      height: 21px;
      font-size: 13px;
    }
  }

  & .telwrap {
    position: relative;
  }

  & .closebtn {
    position: absolute;
    right: 15px;
    top: 13px;
  }

  & .imglist {
    display: grid;
    grid-template-columns: 100px 1fr;
  }

  & .titlewrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  & .addbtn {
    color: #7d7d7d;
    font-size: 14px;
  }
  & .between {
    display: flex;
    align-items: center;
    & .css-4jnixx-MuiStack-root > .MuiTextField-root {
      min-width: 80px;
    }
  }

  & .bottomNone {
    margin-bottom: 0px !important;
  }

  & .cnt {
    position: relative;
    right: 20px;
  }
`;

export default AddStorePage;
