import React, { useEffect, useState } from "react";
import NavigationHeader from "../components/Header/NavigationHeader";
import styled from "styled-components";
import apis from "../api/apis";
import axios from "axios";
import ImgPicker from "../components/ImgPicker/ImgPicker";

const EditImgPage = () => {
  const [data, setData] = useState();
  const [prevDetail, setPrevDetail] = useState();

  const storeId = localStorage.getItem("storeId");
  useEffect(() => {
    axios.all([apis.getStoreDetail(storeId), apis.getStoreImg(storeId)]).then(
      axios.spread((res1, res2) => {
        const main = res1.data.data;
        const detail = res2.data.list;
        const mainImg = {
          store_img_data: main?.store_main_simg,
          uuid: "main",
        };
        setPrevDetail(detail);
        if (!detail) {
          setData([mainImg]);
        } else {
          setData([mainImg, ...detail]);
        }
      })
    );
  }, []);

  console.log(data);

  return (
    <EditImg>
      <NavigationHeader title="이미지 수정" value="back" />
      <div className="container">
        {data && <ImgPicker img={data} setImg={setData} value={"edit"} />}
      </div>
    </EditImg>
  );
};

const EditImg = styled.div`
  & .container {
    padding-top: 70px;
  }
`;

export default EditImgPage;
