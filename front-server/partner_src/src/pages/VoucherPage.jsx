import React, { useEffect, useState } from "react";

import apis from "../api/apis";

import NavigationHeader from "../components/Header/NavigationHeader";

import { BtnWrap, Btn } from "../styles/styledComponent";
import VoucherCard from "../components/Voucher/VoucherCard";
import styled from "styled-components";
import { useNavigate } from "react-router-dom";

const VoucherPage = () => {
  const navigate = useNavigate();
  const storeId = localStorage.getItem("storeId");
  const [voucherList, setVoucherList] = useState();
  const [deleteBtn, setDeleteBtn] = useState(false);
  const [deletVoucher, setDelteVoucher] = useState(null);
  const [update, setUpdate] = useState();

  useEffect(() => {
    apis.getVoucherList(storeId).then((res) => {
      setVoucherList(res.data.list);
    });
  }, [update]);

  const onClickDeleteBtn = () => {
    setDeleteBtn(!deleteBtn);
    if (deleteBtn) {
      setDelteVoucher(null);
    }
  };

  const onClickCheck = (id) => {
    const arr = [];
    if (!deletVoucher) {
      arr.push(id);
    } else {
      if (deletVoucher.includes(id)) {
        const result = deletVoucher.filter((el) => el !== id);
        arr.push(...result);
      } else {
        arr.push(...deletVoucher, id);
      }
    }
    setDelteVoucher(arr);
  };

  const onClickDelete = () => {
    const arr = [];
    deletVoucher.map((id) => {
      apis.deleteVoucher(storeId, id).then((res) => {
        if (res.data.result === "000") {
          arr.push(id);
        }
      });
    });

    setTimeout(() => {
      setUpdate(arr[0]);
      setDeleteBtn(false);
    }, 1000);
  };

  return (
    <Voucher>
      <NavigationHeader title={"쿠폰 관리"} value={"nav"} />
      <div className="list">
        {voucherList &&
          voucherList.map((voucher, key) => {
            return (
              <div key={key}>
                <VoucherCard
                  data={voucher}
                  deleteBtn={deleteBtn}
                  checkValue={deletVoucher?.includes(voucher.store_voucher_idx)}
                  onClickCheck={onClickCheck}
                />
              </div>
            );
          })}
      </div>

      {deleteBtn ? (
        <BtnWrap>
          <Btn bg={"#7d7d7d"} onClick={() => onClickDeleteBtn()}>
            취소
          </Btn>
          <Btn onClick={() => onClickDelete()}>
            {!deletVoucher ? 0 : deletVoucher?.length}개, 삭제
          </Btn>
        </BtnWrap>
      ) : (
        <BtnWrap>
          <Btn bg={"#7d7d7d"} onClick={() => onClickDeleteBtn()}>
            {deleteBtn ? "취소" : "쿠폰 삭제"}
          </Btn>
          <Btn bg={"#f33562"} onClick={() => navigate("/addvoucher")}>
            새로 만들기
          </Btn>
        </BtnWrap>
      )}
    </Voucher>
  );
};

const Voucher = styled.div`
  position: relative;
  width: 100%;

  & .list {
    width: 100%;
    height: calc(100vh - 90px);
    box-sizing: border-box;
    padding: 90px 20px 50px 20px;
    overflow-y: scroll;
  }
`;

export default VoucherPage;
